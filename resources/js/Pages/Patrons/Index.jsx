import React from 'react';
import {
    Box,
    Heading,
    Text,
    VStack,
    Alert,
    AlertIcon,
    AlertTitle,
    AlertDescription,
} from '@chakra-ui/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function PatronsIndex() {
    return (
        <AuthenticatedLayout>
            <VStack spacing={6} align="stretch">
                <Box>
                    <Heading size="lg" color="blue.600" mb={2}>
                        Patron Management
                    </Heading>
                    <Text color="gray.600">
                        Track your patron favor, relationships, and available rewards across all DDO patrons.
                    </Text>
                </Box>

                <Alert status="info" borderRadius="md">
                    <AlertIcon />
                    <Box>
                        <AlertTitle>Coming Soon!</AlertTitle>
                        <AlertDescription>
                            Patron management features are currently under development. 
                            This will include favor tracking, reward management, and patron-specific quests.
                        </AlertDescription>
                    </Box>
                </Alert>

                <Box bg="white" p={6} borderRadius="lg" shadow="sm" border="1px" borderColor="gray.200">
                    <Heading size="md" mb={4}>Planned Features</Heading>
                    <VStack align="stretch" spacing={2}>
                        <Text>• Patron favor tracking and progress</Text>
                        <Text>• Available rewards and unlock requirements</Text>
                        <Text>• Patron-specific quest recommendations</Text>
                        <Text>• Favor calculation and optimization</Text>
                        <Text>• Reward comparison and planning</Text>
                    </VStack>
                </Box>
            </VStack>
        </AuthenticatedLayout>
    );
}
