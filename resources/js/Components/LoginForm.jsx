import React, { useState, useCallback } from 'react';
import {
    Button,
    FormControl,
    FormLabel,
    FormErrorMessage,
    Input,
    Stack,
    Text,
    Divider,
    VStack,
    Alert,
    AlertIcon,
    InputGroup,
    InputRightElement,
    IconButton,
    Box
} from '@chakra-ui/react';
import { Link, useForm } from '@inertiajs/react';
import { ViewIcon, ViewOffIcon } from '@chakra-ui/icons';

export default function LoginForm({ errors = {}, flash = {} }) {
    const [showPassword, setShowPassword] = useState(false);
    
    const { data, setData, post, processing, errors: formErrors = {} } = useForm({
        email: '',
        password: '',
    });

    const togglePasswordVisibility = useCallback(() => {
        setShowPassword(prev => !prev);
    }, []);

    const handleSubmit = useCallback((e) => {
        e.preventDefault();
        post('/login');
    }, [post]);

    const handleEmailChange = useCallback((e) => {
        setData('email', e.target.value);
    }, [setData]);

    const handlePasswordChange = useCallback((e) => {
        setData('password', e.target.value);
    }, [setData]);

    // Combine server and client-side errors
    const emailError = errors?.email || formErrors?.email;
    const passwordError = errors?.password || formErrors?.password;
    const generalError = errors?.message || formErrors?.message;

    return (
        <VStack spacing={6} w="full">
            {generalError && (
                <Alert status="error" borderRadius="md">
                    <AlertIcon />
                    {generalError}
                </Alert>
            )}

            {flash?.message && (
                <Alert status="success" borderRadius="md">
                    <AlertIcon />
                    {flash.message}
                </Alert>
            )}

            <Box as="form" onSubmit={handleSubmit} w="full">
                <Stack spacing={4}>
                    <FormControl isInvalid={!!emailError} isRequired>
                        <FormLabel>Email Address</FormLabel>
                        <Input 
                            type="email" 
                            value={data.email}
                            onChange={handleEmailChange}
                            placeholder="Enter your email"
                            autoComplete="email"
                        />
                        <FormErrorMessage>{emailError}</FormErrorMessage>
                    </FormControl>

                    <FormControl isInvalid={!!passwordError} isRequired>
                        <FormLabel>Password</FormLabel>
                        <InputGroup>
                            <Input
                                type={showPassword ? 'text' : 'password'}
                                value={data.password}
                                onChange={handlePasswordChange}
                                placeholder="Enter your password"
                                autoComplete="current-password"
                            />
                            <InputRightElement>
                                <IconButton
                                    variant="ghost"
                                    aria-label={showPassword ? 'Hide password' : 'Show password'}
                                    icon={showPassword ? <ViewOffIcon /> : <ViewIcon />}
                                    onClick={togglePasswordVisibility}
                                    size="sm"
                                />
                            </InputRightElement>
                        </InputGroup>
                        <FormErrorMessage>{passwordError}</FormErrorMessage>
                    </FormControl>

                    <Button 
                        type="submit"
                        colorScheme="blue" 
                        width="full" 
                        size="lg"
                        isLoading={processing}
                        loadingText="Signing In..."
                        isDisabled={!data.email || !data.password}
                    >
                        Sign In
                    </Button>
                </Stack>
            </Box>

            <Box w="full">
                <Divider />
                <Text textAlign="center" fontSize="sm" color="gray.500" mt={4} mb={4}>
                    Or continue with
                </Text>
                <Button 
                    as="a"
                    href="/auth/google"
                    width="full" 
                    colorScheme="red" 
                    variant="outline"
                    size="lg"
                >
                    Sign in with Google
                </Button>
            </Box>

            <Text textAlign="center" fontSize="sm" color="gray.500">
                Don't have an account?{' '}
                <Link href="/register">
                    <Text as="span" color="blue.500" fontWeight="medium" _hover={{ textDecoration: 'underline' }}>
                        Create one here
                    </Text>
                </Link>
            </Text>
        </VStack>
    );
}
