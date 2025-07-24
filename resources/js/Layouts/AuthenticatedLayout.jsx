import React from 'react';
import {
    Box,
    Flex,
    Avatar,
    Menu,
    MenuButton,
    MenuList,
    MenuItem,
    Text,
    useColorModeValue,
} from '@chakra-ui/react';
import { Link, usePage } from '@inertiajs/react';

export default function AuthenticatedLayout({ children }) {
    const { auth } = usePage().props;
    const bg = useColorModeValue('gray.100', 'gray.900');
    const borderColor = useColorModeValue('gray.200', 'gray.700');

    return (
        <Box minH="100vh" bg={bg}>
            <Flex
                as="header"
                bg={useColorModeValue('white', 'gray.800')}
                borderBottom="1px"
                borderColor={borderColor}
                justifyContent="space-between"
                alignItems="center"
                px={4}
                h={16}
            >
                <Box>
                    {/* You can add a logo or site title here */}
                    <Link href="/dashboard">
                        <Text fontSize="lg" fontWeight="bold">DDO</Text>
                    </Link>
                </Box>

                <Flex alignItems={'center'}>
                    {auth?.user ? (
                        <Menu>
                            <MenuButton
                                as={Box}
                                cursor={'pointer'}
                                _hover={{ textDecoration: 'none' }}
                            >
                                <Flex alignItems="center">
                                    <Text mr={3}>{auth.user.name}</Text>
                                    <Avatar
                                        size={'sm'}
                                        name={auth.user.name}
                                    />
                                </Flex>
                            </MenuButton>
                            <MenuList>
                                <Link href="/account">
                                    <MenuItem>My Account</MenuItem>
                                </Link>
                                <Link href="/logout" method="post" as="button" style={{ width: '100%' }}>
                                    <MenuItem>Logout</MenuItem>
                                </Link>
                            </MenuList>
                        </Menu>
                    ) : (
                        <Text>Not logged in</Text>
                    )}
                </Flex>
            </Flex>
            <Box as="main" p="8">
                {children}
            </Box>
        </Box>
    );
}
