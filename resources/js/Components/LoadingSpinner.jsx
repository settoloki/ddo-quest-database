import React from 'react';
import {
    VStack,
    Spinner,
    Text,
} from '@chakra-ui/react';

export default function LoadingSpinner({ message = "Loading...", size = "xl", ...props }) {
    return (
        <VStack spacing={6} align="center" justify="center" minH="400px" {...props}>
            <Spinner size={size} color="blue.500" />
            <Text>{message}</Text>
        </VStack>
    );
}
