import React from 'react';
import {
    Box,
    Heading,
    Text,
    VStack,
    HStack,
    Avatar,
    Button,
    FormControl,
    FormLabel,
    Input,
} from '@chakra-ui/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InfoAlert from '@/Components/InfoAlert';
import PageHeader from '@/Components/PageHeader';
import { usePage } from '@inertiajs/react';

export default function Profile() {
    const { auth } = usePage().props;

    return (
        <AuthenticatedLayout>
            <VStack spacing={6} align="stretch">
                <PageHeader 
                    title="Profile Settings"
                    description="Manage your account information and preferences."
                />

                <Box bg="white" p={6} borderRadius="lg" shadow="sm" border="1px" borderColor="gray.200">
                    <VStack spacing={6} align="stretch">
                        <HStack spacing={4}>
                            <Avatar
                                size="lg"
                                name={auth.user.name}
                                src={auth.user.avatar}
                            />
                            <VStack align="start" spacing={1}>
                                <Heading size="md">{auth.user.name}</Heading>
                                <Text color="gray.600">{auth.user.email}</Text>
                            </VStack>
                        </HStack>

                        <VStack spacing={4} align="stretch">
                            <FormControl>
                                <FormLabel>Name</FormLabel>
                                <Input defaultValue={auth.user.name} />
                            </FormControl>

                            <FormControl>
                                <FormLabel>Email</FormLabel>
                                <Input defaultValue={auth.user.email} type="email" />
                            </FormControl>

                            <InfoAlert 
                                title="Profile Management"
                                description="Profile editing functionality is coming soon. Currently displaying your account information from Google OAuth."
                            />

                            <HStack spacing={3}>
                                <Button colorScheme="blue" isDisabled>
                                    Save Changes
                                </Button>
                                <Button variant="outline" isDisabled>
                                    Change Password
                                </Button>
                            </HStack>
                        </VStack>
                    </VStack>
                </Box>
            </VStack>
        </AuthenticatedLayout>
    );
}
