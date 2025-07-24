import React from 'react';
import {
    Box,
    Container,
    VStack,
    Card,
    CardBody,
    Heading,
    Text
} from '@chakra-ui/react';

export default function AuthLayout({ 
    children, 
    title = "Welcome", 
    subtitle = "Sign in to your account",
    maxWidth = "400px"
}) {
    return (
        <Box
            minH="100vh"
            bgImage="url('/ddo-background.jpg')"
            bgSize="cover"
            bgPosition="center"
            bgRepeat="no-repeat"
            position="relative"
        >
            {/* Overlay for better text readability */}
            <Box
                position="absolute"
                top="0"
                left="0"
                right="0"
                bottom="0"
                bg="blackAlpha.400"
                zIndex="1"
            />
            
            {/* Content */}
            <Container
                maxW="md"
                centerContent
                position="relative"
                zIndex="2"
                minH="100vh"
                display="flex"
                alignItems="center"
                justifyContent="center"
                py={8}
            >
                <Card
                    bg="white"
                    shadow="2xl"
                    borderRadius="xl"
                    p={8}
                    w="full"
                    maxW={maxWidth}
                >
                    <CardBody>
                        <VStack spacing={6}>
                            <VStack spacing={2} textAlign="center">
                                <Heading size="lg" color="gray.700">
                                    {title}
                                </Heading>
                                <Text color="gray.500">
                                    {subtitle}
                                </Text>
                            </VStack>

                            {children}
                        </VStack>
                    </CardBody>
                </Card>
            </Container>
        </Box>
    );
}
