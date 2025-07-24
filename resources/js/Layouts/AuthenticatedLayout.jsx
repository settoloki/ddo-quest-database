import React from 'react';
import {
    Box,
    Flex,
    HStack,
    VStack,
    Avatar,
    Menu,
    MenuButton,
    MenuList,
    MenuItem,
    Text,
    Button,
    IconButton,
    useColorModeValue,
    useDisclosure,
    Drawer,
    DrawerBody,
    DrawerHeader,
    DrawerOverlay,
    DrawerContent,
    DrawerCloseButton,
    Badge,
    Spacer,
} from '@chakra-ui/react';
import { HamburgerIcon, ViewIcon, StarIcon, TimeIcon, SettingsIcon } from '@chakra-ui/icons';
import { Link, usePage } from '@inertiajs/react';

const navItems = [
    {
        name: 'Dashboard',
        href: '/dashboard',
        icon: ViewIcon,
        description: 'Overview and statistics'
    },
    {
        name: 'Quests',
        href: '/quests',
        icon: StarIcon,
        description: 'Quest management and tracking'
    },
    {
        name: 'Patrons',
        href: '/patrons',
        icon: SettingsIcon,
        description: 'Patron favor and relationships'
    },
    {
        name: 'Adventure Packs',
        href: '/adventure-packs',
        icon: TimeIcon,
        description: 'Content pack organization'
    },
];

function NavLink({ href, children, icon: Icon, isActive, description, isMobile = false }) {
    const linkColor = useColorModeValue('gray.600', 'gray.300');
    const activeBg = useColorModeValue('blue.50', 'blue.900');
    const activeColor = useColorModeValue('blue.600', 'blue.200');
    const hoverBg = useColorModeValue('gray.100', 'gray.700');

    return (
        <Link href={href}>
            <Button
                variant={isActive ? 'solid' : 'ghost'}
                colorScheme={isActive ? 'blue' : undefined}
                size={isMobile ? 'lg' : 'md'}
                w={isMobile ? 'full' : 'auto'}
                justifyContent={isMobile ? 'flex-start' : 'center'}
                leftIcon={Icon ? <Icon /> : undefined}
                bg={isActive ? activeBg : 'transparent'}
                color={isActive ? activeColor : linkColor}
                _hover={{
                    bg: isActive ? activeBg : hoverBg,
                    textDecoration: 'none',
                }}
                fontWeight={isActive ? 'semibold' : 'normal'}
            >
                <VStack spacing={0} align={isMobile ? 'flex-start' : 'center'}>
                    <Text>{children}</Text>
                    {isMobile && description && (
                        <Text fontSize="xs" color="gray.500" fontWeight="normal">
                            {description}
                        </Text>
                    )}
                </VStack>
            </Button>
        </Link>
    );
}

function MobileNav({ isOpen, onClose, currentPath, auth }) {
    return (
        <Drawer isOpen={isOpen} placement="left" onClose={onClose}>
            <DrawerOverlay />
            <DrawerContent>
                <DrawerCloseButton />
                <DrawerHeader borderBottomWidth="1px">
                    <Flex alignItems="center">
                        <Text fontSize="xl" fontWeight="bold">DDO Quest DB</Text>
                        <Badge ml={2} colorScheme="blue" variant="subtle">Beta</Badge>
                    </Flex>
                </DrawerHeader>
                <DrawerBody>
                    <VStack spacing={2} align="stretch" mt={4}>
                        {navItems.map((item) => (
                            <NavLink
                                key={item.name}
                                href={item.href}
                                icon={item.icon}
                                isActive={currentPath === item.href || currentPath.startsWith(item.href + '/')}
                                description={item.description}
                                isMobile={true}
                            >
                                {item.name}
                            </NavLink>
                        ))}
                        
                        <Box mt={8} pt={4} borderTopWidth="1px">
                            <Text fontSize="sm" color="gray.500" mb={2}>Account</Text>
                            <VStack spacing={2} align="stretch">
                                <Link href="/profile">
                                    <Button variant="ghost" w="full" justifyContent="flex-start">
                                        Profile Settings
                                    </Button>
                                </Link>
                                <Link href="/logout" method="post" as="button" style={{ width: '100%' }}>
                                    <Button variant="ghost" w="full" justifyContent="flex-start" colorScheme="red">
                                        Logout
                                    </Button>
                                </Link>
                            </VStack>
                        </Box>
                    </VStack>
                </DrawerBody>
            </DrawerContent>
        </Drawer>
    );
}

export default function AuthenticatedLayout({ children }) {
    const { auth, currentPath = '' } = usePage().props;
    const { isOpen, onOpen, onClose } = useDisclosure();
    
    const bg = useColorModeValue('gray.50', 'gray.900');
    const headerBg = useColorModeValue('white', 'gray.800');
    const borderColor = useColorModeValue('gray.200', 'gray.700');

    return (
        <Box minH="100vh" bg={bg}>
            {/* Header */}
            <Flex
                as="header"
                bg={headerBg}
                borderBottom="1px"
                borderColor={borderColor}
                justifyContent="space-between"
                alignItems="center"
                px={4}
                h={16}
                position="sticky"
                top={0}
                zIndex="sticky"
                boxShadow="sm"
            >
                {/* Logo and Mobile Menu Button */}
                <Flex alignItems="center">
                    <IconButton
                        aria-label="Open navigation menu"
                        icon={<HamburgerIcon />}
                        variant="ghost"
                        display={{ base: 'flex', md: 'none' }}
                        onClick={onOpen}
                        mr={2}
                    />
                    <Link href="/dashboard">
                        <Flex alignItems="center" cursor="pointer">
                            <Text fontSize="xl" fontWeight="bold" color="blue.600">DDO</Text>
                            <Text fontSize="xl" fontWeight="normal" ml={1}>Quest DB</Text>
                            <Badge ml={2} colorScheme="blue" variant="subtle" fontSize="xs">Beta</Badge>
                        </Flex>
                    </Link>
                </Flex>

                {/* Desktop Navigation */}
                <HStack spacing={1} display={{ base: 'none', md: 'flex' }}>
                    {navItems.map((item) => (
                        <NavLink
                            key={item.name}
                            href={item.href}
                            icon={item.icon}
                            isActive={currentPath === item.href || currentPath.startsWith(item.href + '/')}
                        >
                            {item.name}
                        </NavLink>
                    ))}
                </HStack>

                {/* User Menu */}
                <Flex alignItems="center">
                    {auth?.user ? (
                        <Menu>
                            <MenuButton
                                as={Button}
                                variant="ghost"
                                cursor="pointer"
                                _hover={{ bg: useColorModeValue('gray.100', 'gray.700') }}
                                rightIcon={null}
                            >
                                <Flex alignItems="center">
                                    <Text mr={3} display={{ base: 'none', sm: 'block' }}>
                                        {auth.user.name}
                                    </Text>
                                    <Avatar
                                        size="sm"
                                        name={auth.user.name}
                                        src={auth.user.avatar}
                                    />
                                </Flex>
                            </MenuButton>
                            <MenuList>
                                <MenuItem as="div">
                                    <Link href="/profile" style={{ width: '100%' }}>
                                        Profile Settings
                                    </Link>
                                </MenuItem>
                                <MenuItem as="div">
                                    <Link href="/account" style={{ width: '100%' }}>
                                        My Account
                                    </Link>
                                </MenuItem>
                                <MenuItem as="div">
                                    <Link href="/logout" method="post" as="button" style={{ width: '100%' }}>
                                        <Text color="red.500">Logout</Text>
                                    </Link>
                                </MenuItem>
                            </MenuList>
                        </Menu>
                    ) : (
                        <Text>Not logged in</Text>
                    )}
                </Flex>
            </Flex>

            {/* Mobile Navigation Drawer */}
            <MobileNav 
                isOpen={isOpen} 
                onClose={onClose} 
                currentPath={currentPath}
                auth={auth}
            />

            {/* Main Content */}
            <Box as="main" p={{ base: 4, md: 8 }} maxW="7xl" mx="auto">
                {children}
            </Box>
        </Box>
    );
}
