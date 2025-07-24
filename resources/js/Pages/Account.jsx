import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Heading, Text } from '@chakra-ui/react';

export default function Account() {
    return (
        <AuthenticatedLayout>
            <Heading>My Account</Heading>
            <Text>This is your account page.</Text>
        </AuthenticatedLayout>
    );
}
