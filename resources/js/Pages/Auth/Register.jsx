import React, { useState } from 'react';
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
import AuthLayout from '../../Layouts/AuthLayout';

export default function Register({ errors }) {
    const [showPassword, setShowPassword] = useState(false);
    const [showPasswordConfirmation, setShowPasswordConfirmation] = useState(false);
    
    const { data, setData, post, processing } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/register');
    };

    return (
        <AuthLayout
            title="Join DDO Community"
            subtitle="Create your account to get started"
            maxWidth="450px"
        >
            <VStack spacing={6} w="full">
                {errors.message && (
                    <Alert status="error" borderRadius="md">
                        <AlertIcon />
                        {errors.message}
                    </Alert>
                )}

                <Box as="form" onSubmit={submit} w="full">
                    <Stack spacing={4}>
                        <FormControl isInvalid={errors.name} isRequired>
                            <FormLabel>Full Name</FormLabel>
                            <Input 
                                type="text" 
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                placeholder="Enter your full name"
                                autoComplete="name"
                            />
                            <FormErrorMessage>{errors.name}</FormErrorMessage>
                        </FormControl>

                        <FormControl isInvalid={errors.email} isRequired>
                            <FormLabel>Email Address</FormLabel>
                            <Input 
                                type="email" 
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                placeholder="Enter your email"
                                autoComplete="email"
                            />
                            <FormErrorMessage>{errors.email}</FormErrorMessage>
                        </FormControl>

                        <FormControl isInvalid={errors.password} isRequired>
                            <FormLabel>Password</FormLabel>
                            <InputGroup>
                                <Input
                                    type={showPassword ? 'text' : 'password'}
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    placeholder="Create a password"
                                    autoComplete="new-password"
                                />
                                <InputRightElement>
                                    <IconButton
                                        variant="ghost"
                                        aria-label={showPassword ? 'Hide password' : 'Show password'}
                                        icon={showPassword ? <ViewOffIcon /> : <ViewIcon />}
                                        onClick={() => setShowPassword(!showPassword)}
                                        size="sm"
                                    />
                                </InputRightElement>
                            </InputGroup>
                            <FormErrorMessage>{errors.password}</FormErrorMessage>
                        </FormControl>

                        <FormControl isInvalid={errors.password_confirmation} isRequired>
                            <FormLabel>Confirm Password</FormLabel>
                            <InputGroup>
                                <Input
                                    type={showPasswordConfirmation ? 'text' : 'password'}
                                    value={data.password_confirmation}
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    placeholder="Confirm your password"
                                    autoComplete="new-password"
                                />
                                <InputRightElement>
                                    <IconButton
                                        variant="ghost"
                                        aria-label={showPasswordConfirmation ? 'Hide password' : 'Show password'}
                                        icon={showPasswordConfirmation ? <ViewOffIcon /> : <ViewIcon />}
                                        onClick={() => setShowPasswordConfirmation(!showPasswordConfirmation)}
                                        size="sm"
                                    />
                                </InputRightElement>
                            </InputGroup>
                            <FormErrorMessage>{errors.password_confirmation}</FormErrorMessage>
                        </FormControl>

                        <Button 
                            type="submit"
                            colorScheme="blue" 
                            width="full" 
                            size="lg"
                            isLoading={processing}
                            loadingText="Creating Account..."
                        >
                            Create Account
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
                        Sign up with Google
                    </Button>
                </Box>

                <Text textAlign="center" fontSize="sm" color="gray.500">
                    Already have an account?{' '}
                    <Link href="/login">
                        <Text as="span" color="blue.500" fontWeight="medium" _hover={{ textDecoration: 'underline' }}>
                            Sign in here
                        </Text>
                    </Link>
                </Text>
            </VStack>
        </AuthLayout>
    );
}
