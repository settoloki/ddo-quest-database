import React from 'react';
import {
    Alert,
    AlertIcon,
    AlertTitle,
    AlertDescription,
    Box,
} from '@chakra-ui/react';

export default function InfoAlert({ title, description, ...props }) {
    return (
        <Alert status="info" borderRadius="md" {...props}>
            <AlertIcon />
            <Box>
                <AlertTitle>{title}</AlertTitle>
                <AlertDescription>
                    {description}
                </AlertDescription>
            </Box>
        </Alert>
    );
}
