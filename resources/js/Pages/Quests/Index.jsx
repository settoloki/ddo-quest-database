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

export default function QuestsIndex() {
    return (
        <AuthenticatedLayout>
            <VStack spacing={6} align="stretch">
                <Box>
                    <Heading size="lg" color="blue.600" mb={2}>
                        Quest Management
                    </Heading>
                    <Text color="gray.600">
                        Manage and track your DDO quest progress, completions, and objectives.
                    </Text>
                </Box>

                <Alert status="info" borderRadius="md">
                    <AlertIcon />
                    <Box>
                        <AlertTitle>Coming Soon!</AlertTitle>
                        <AlertDescription>
                            Quest management features are currently under development. 
                            This will include quest tracking, completion status, and detailed quest information.
                        </AlertDescription>
                    </Box>
                </Alert>

                <Box bg="white" p={6} borderRadius="lg" shadow="sm" border="1px" borderColor="gray.200">
                    <Heading size="md" mb={4}>Planned Features</Heading>
                    <VStack align="stretch" spacing={2}>
                        <Text>• Quest search and filtering</Text>
                        <Text>• Completion tracking and progress</Text>
                        <Text>• Quest difficulty and reward information</Text>
                        <Text>• Patron favor tracking</Text>
                        <Text>• Quest chains and dependencies</Text>
                    </VStack>
                </Box>
            </VStack>
        </AuthenticatedLayout>
    );
}
