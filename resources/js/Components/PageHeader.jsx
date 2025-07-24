import React from 'react';
import {
    Box,
    Heading,
    Text,
    Flex,
    HStack,
} from '@chakra-ui/react';

export default function PageHeader({ title, description, actions, ...props }) {
    return (
        <Flex justify="space-between" align="center" wrap="wrap" gap={4} {...props}>
            <Box>
                <Heading size="lg" color="blue.600" mb={2}>
                    {title}
                </Heading>
                {description && (
                    <Text color="gray.600">
                        {description}
                    </Text>
                )}
            </Box>
            {actions && (
                <HStack>
                    {actions}
                </HStack>
            )}
        </Flex>
    );
}
