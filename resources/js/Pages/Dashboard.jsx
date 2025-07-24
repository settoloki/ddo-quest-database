import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Heading, Text } from '@chakra-ui/react';

export default function Dashboard() {
    return (
        <AuthenticatedLayout>
            <Heading>Dashboard</Heading>
            <Text>Welcome to your dashboard!</Text>
        </AuthenticatedLayout>
    );
}
