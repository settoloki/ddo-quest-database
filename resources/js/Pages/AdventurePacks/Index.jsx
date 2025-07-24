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

export default function AdventurePacksIndex() {
    return (
        <AuthenticatedLayout>
            <VStack spacing={6} align="stretch">
                <Box>
                    <Heading size="lg" color="blue.600" mb={2}>
                        Adventure Pack Management
                    </Heading>
                    <Text color="gray.600">
                        Organize and track your DDO adventure packs, content access, and pack-specific quests.
                    </Text>
                </Box>

                <Alert status="info" borderRadius="md">
                    <AlertIcon />
                    <Box>
                        <AlertTitle>Coming Soon!</AlertTitle>
                        <AlertDescription>
                            Adventure pack management features are currently under development. 
                            This will include pack organization, quest tracking, and content access management.
                        </AlertDescription>
                    </Box>
                </Alert>

                <Box bg="white" p={6} borderRadius="lg" shadow="sm" border="1px" borderColor="gray.200">
                    <Heading size="md" mb={4}>Planned Features</Heading>
                    <VStack align="stretch" spacing={2}>
                        <Text>• Adventure pack catalog and organization</Text>
                        <Text>• Pack-specific quest tracking</Text>
                        <Text>• Content access and ownership management</Text>
                        <Text>• Pack completion statistics</Text>
                        <Text>• Recommended pack progression paths</Text>
                    </VStack>
                </Box>
            </VStack>
        </AuthenticatedLayout>
    );
}
