import React, { useState, useEffect } from 'react';
import {
    Box,
    Heading,
    Text,
    VStack,
    HStack,
    Flex,
    Input,
    Select,
    Button,
    Table,
    Thead,
    Tbody,
    Tr,
    Th,
    Td,
    Badge,
    Spinner,
    Alert,
    AlertIcon,
    AlertTitle,
    AlertDescription,
    InputGroup,
    InputLeftElement,
    Grid,
    GridItem,
    Card,
    CardBody,
    IconButton,
    Menu,
    MenuButton,
    MenuList,
    MenuItem,
    useDisclosure,
    Modal,
    ModalOverlay,
    ModalContent,
    ModalHeader,
    ModalFooter,
    ModalBody,
    ModalCloseButton,
    useToast,
    Stat,
    StatLabel,
    StatNumber,
    StatHelpText,
} from '@chakra-ui/react';
import { SearchIcon, AddIcon, EditIcon, ViewIcon, DeleteIcon, DownloadIcon } from '@chakra-ui/icons';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import axios from 'axios';

export default function QuestsIndex() {
    const [quests, setQuests] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [searchTerm, setSearchTerm] = useState('');
    const [selectedPatron, setSelectedPatron] = useState('');
    const [selectedAdventurePack, setSelectedAdventurePack] = useState('');
    const [selectedDifficulty, setSelectedDifficulty] = useState('');
    const [patrons, setPatrons] = useState([]);
    const [adventurePacks, setAdventurePacks] = useState([]);
    const [difficulties, setDifficulties] = useState([]);
    const [sortBy, setSortBy] = useState('name');
    const [sortOrder, setSortOrder] = useState('asc');
    const [currentPage, setCurrentPage] = useState(1);
    const [questsPerPage] = useState(10);
    
    const { isOpen, onOpen, onClose } = useDisclosure();
    const [selectedQuest, setSelectedQuest] = useState(null);
    const toast = useToast();

    // Load initial data
    useEffect(() => {
        loadQuests();
        loadReferenceData();
    }, []);

    const loadQuests = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/api/v1/quests');
            setQuests(response.data.data || []);
            setError(null);
        } catch (err) {
            setError('Failed to load quests. Please try again.');
            console.error('Error loading quests:', err);
        } finally {
            setLoading(false);
        }
    };

    const loadReferenceData = async () => {
        try {
            const [patronsRes, packsRes, difficultiesRes] = await Promise.all([
                axios.get('/api/v1/patrons'),
                axios.get('/api/v1/adventure-packs'),
                axios.get('/api/v1/difficulties')
            ]);
            
            setPatrons(patronsRes.data.data || []);
            setAdventurePacks(packsRes.data.data || []);
            setDifficulties(difficultiesRes.data.data || []);
        } catch (err) {
            console.error('Error loading reference data:', err);
        }
    };

    // Filter and sort quests
    const filteredQuests = quests.filter(quest => {
        const matchesSearch = quest.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                            quest.overview?.toLowerCase().includes(searchTerm.toLowerCase());
        const matchesPatron = !selectedPatron || quest.patron?.id === parseInt(selectedPatron);
        const matchesAdventurePack = !selectedAdventurePack || quest.adventure_pack?.id === parseInt(selectedAdventurePack);
        const matchesDifficulty = !selectedDifficulty || quest.difficulties?.some(d => d.id === parseInt(selectedDifficulty));
        
        return matchesSearch && matchesPatron && matchesAdventurePack && matchesDifficulty;
    }).sort((a, b) => {
        const aVal = a[sortBy] || '';
        const bVal = b[sortBy] || '';
        
        if (sortOrder === 'asc') {
            return aVal > bVal ? 1 : -1;
        } else {
            return aVal < bVal ? 1 : -1;
        }
    });

    // Pagination
    const indexOfLastQuest = currentPage * questsPerPage;
    const indexOfFirstQuest = indexOfLastQuest - questsPerPage;
    const currentQuests = filteredQuests.slice(indexOfFirstQuest, indexOfLastQuest);
    const totalPages = Math.ceil(filteredQuests.length / questsPerPage);

    const handleQuestView = (quest) => {
        setSelectedQuest(quest);
        onOpen();
    };

    const clearFilters = () => {
        setSearchTerm('');
        setSelectedPatron('');
        setSelectedAdventurePack('');
        setSelectedDifficulty('');
        setCurrentPage(1);
    };

    const getDifficultyColor = (difficulty) => {
        const colors = {
            'Casual': 'green',
            'Normal': 'blue',
            'Hard': 'orange',
            'Elite': 'red',
            'Reaper': 'purple'
        };
        return colors[difficulty] || 'gray';
    };

    if (loading) {
        return (
            <AuthenticatedLayout>
                <VStack spacing={6} align="center" justify="center" minH="400px">
                    <Spinner size="xl" color="blue.500" />
                    <Text>Loading quests...</Text>
                </VStack>
            </AuthenticatedLayout>
        );
    }

    return (
        <AuthenticatedLayout>
            <VStack spacing={6} align="stretch">
                {/* Header */}
                <Flex justify="space-between" align="center" wrap="wrap" gap={4}>
                    <Box>
                        <Heading size="lg" color="blue.600" mb={2}>
                            Quest Management
                        </Heading>
                        <Text color="gray.600">
                            Browse, filter, and manage your DDO quest database
                        </Text>
                    </Box>
                    <HStack>
                        <Button leftIcon={<AddIcon />} colorScheme="blue" isDisabled>
                            Add Quest
                        </Button>
                        <Button leftIcon={<DownloadIcon />} variant="outline" isDisabled>
                            Export
                        </Button>
                    </HStack>
                </Flex>

                {/* Statistics Cards */}
                <Grid templateColumns={{ base: '1fr', md: 'repeat(4, 1fr)' }} gap={4}>
                    <Card>
                        <CardBody>
                            <Stat>
                                <StatLabel>Total Quests</StatLabel>
                                <StatNumber>{quests.length}</StatNumber>
                                <StatHelpText>In database</StatHelpText>
                            </Stat>
                        </CardBody>
                    </Card>
                    <Card>
                        <CardBody>
                            <Stat>
                                <StatLabel>Filtered Results</StatLabel>
                                <StatNumber>{filteredQuests.length}</StatNumber>
                                <StatHelpText>Current view</StatHelpText>
                            </Stat>
                        </CardBody>
                    </Card>
                    <Card>
                        <CardBody>
                            <Stat>
                                <StatLabel>Adventure Packs</StatLabel>
                                <StatNumber>{adventurePacks.length}</StatNumber>
                                <StatHelpText>Available</StatHelpText>
                            </Stat>
                        </CardBody>
                    </Card>
                    <Card>
                        <CardBody>
                            <Stat>
                                <StatLabel>Patrons</StatLabel>
                                <StatNumber>{patrons.length}</StatNumber>
                                <StatHelpText>Active</StatHelpText>
                            </Stat>
                        </CardBody>
                    </Card>
                </Grid>

                {/* Search and Filters */}
                <Card>
                    <CardBody>
                        <VStack spacing={4}>
                            <Grid templateColumns={{ base: '1fr', md: '2fr 1fr 1fr 1fr' }} gap={4} w="full">
                                <InputGroup>
                                    <InputLeftElement>
                                        <SearchIcon color="gray.300" />
                                    </InputLeftElement>
                                    <Input
                                        placeholder="Search quests by name or overview..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                    />
                                </InputGroup>
                                
                                <Select
                                    placeholder="All Patrons"
                                    value={selectedPatron}
                                    onChange={(e) => setSelectedPatron(e.target.value)}
                                >
                                    {patrons.map(patron => (
                                        <option key={patron.id} value={patron.id}>{patron.name}</option>
                                    ))}
                                </Select>

                                <Select
                                    placeholder="All Adventure Packs"
                                    value={selectedAdventurePack}
                                    onChange={(e) => setSelectedAdventurePack(e.target.value)}
                                >
                                    {adventurePacks.map(pack => (
                                        <option key={pack.id} value={pack.id}>{pack.name}</option>
                                    ))}
                                </Select>

                                <Select
                                    placeholder="All Difficulties"
                                    value={selectedDifficulty}
                                    onChange={(e) => setSelectedDifficulty(e.target.value)}
                                >
                                    {difficulties.map(difficulty => (
                                        <option key={difficulty.id} value={difficulty.id}>{difficulty.name}</option>
                                    ))}
                                </Select>
                            </Grid>

                            <HStack justify="space-between" w="full">
                                <HStack>
                                    <Text fontSize="sm" color="gray.600">Sort by:</Text>
                                    <Select size="sm" value={sortBy} onChange={(e) => setSortBy(e.target.value)} width="auto">
                                        <option value="name">Name</option>
                                        <option value="level">Level</option>
                                        <option value="duration">Duration</option>
                                    </Select>
                                    <Select size="sm" value={sortOrder} onChange={(e) => setSortOrder(e.target.value)} width="auto">
                                        <option value="asc">Ascending</option>
                                        <option value="desc">Descending</option>
                                    </Select>
                                </HStack>
                                <Button size="sm" variant="outline" onClick={clearFilters}>
                                    Clear Filters
                                </Button>
                            </HStack>
                        </VStack>
                    </CardBody>
                </Card>

                {/* Error Alert */}
                {error && (
                    <Alert status="error" borderRadius="md">
                        <AlertIcon />
                        <AlertTitle>Error!</AlertTitle>
                        <AlertDescription>{error}</AlertDescription>
                    </Alert>
                )}

                {/* Quest Table */}
                <Card>
                    <CardBody p={0}>
                        <Box overflowX="auto">
                            <Table variant="simple">
                                <Thead bg="gray.50">
                                    <Tr>
                                        <Th>Quest Name</Th>
                                        <Th>Level</Th>
                                        <Th>Patron</Th>
                                        <Th>Adventure Pack</Th>
                                        <Th>Difficulties</Th>
                                        <Th>Duration</Th>
                                        <Th width="100px">Actions</Th>
                                    </Tr>
                                </Thead>
                                <Tbody>
                                    {currentQuests.length === 0 ? (
                                        <Tr>
                                            <Td colSpan={7} textAlign="center" py={8}>
                                                <VStack spacing={2}>
                                                    <Text color="gray.500">No quests found</Text>
                                                    <Text fontSize="sm" color="gray.400">
                                                        Try adjusting your search criteria
                                                    </Text>
                                                </VStack>
                                            </Td>
                                        </Tr>
                                    ) : (
                                        currentQuests.map(quest => (
                                            <Tr key={quest.id} _hover={{ bg: 'gray.50' }}>
                                                <Td>
                                                    <VStack align="start" spacing={1}>
                                                        <Text fontWeight="medium">{quest.name}</Text>
                                                        {quest.overview && (
                                                            <Text fontSize="sm" color="gray.600" noOfLines={1}>
                                                                {quest.overview}
                                                            </Text>
                                                        )}
                                                    </VStack>
                                                </Td>
                                                <Td>
                                                    <Badge colorScheme="blue" variant="subtle">
                                                        Level {quest.level}
                                                    </Badge>
                                                </Td>
                                                <Td>
                                                    <Text fontSize="sm">
                                                        {quest.patron?.name || 'Unknown'}
                                                    </Text>
                                                </Td>
                                                <Td>
                                                    <Text fontSize="sm">
                                                        {quest.adventure_pack?.name || 'Free to Play'}
                                                    </Text>
                                                </Td>
                                                <Td>
                                                    <HStack spacing={1} flexWrap="wrap">
                                                        {quest.difficulties?.map(difficulty => (
                                                            <Badge
                                                                key={difficulty.id}
                                                                colorScheme={getDifficultyColor(difficulty.name)}
                                                                size="sm"
                                                            >
                                                                {difficulty.name}
                                                            </Badge>
                                                        )) || <Text fontSize="sm" color="gray.400">None</Text>}
                                                    </HStack>
                                                </Td>
                                                <Td>
                                                    <Text fontSize="sm">
                                                        {quest.duration?.name || 'Unknown'}
                                                    </Text>
                                                </Td>
                                                <Td>
                                                    <HStack spacing={1}>
                                                        <IconButton
                                                            aria-label="View quest details"
                                                            icon={<ViewIcon />}
                                                            size="sm"
                                                            variant="ghost"
                                                            onClick={() => handleQuestView(quest)}
                                                        />
                                                        <IconButton
                                                            aria-label="Edit quest"
                                                            icon={<EditIcon />}
                                                            size="sm"
                                                            variant="ghost"
                                                            isDisabled
                                                        />
                                                    </HStack>
                                                </Td>
                                            </Tr>
                                        ))
                                    )}
                                </Tbody>
                            </Table>
                        </Box>
                    </CardBody>
                </Card>

                {/* Pagination */}
                {totalPages > 1 && (
                    <HStack justify="center" spacing={2}>
                        <Button
                            size="sm"
                            onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
                            isDisabled={currentPage === 1}
                        >
                            Previous
                        </Button>
                        
                        {Array.from({ length: Math.min(5, totalPages) }, (_, i) => {
                            const pageNum = currentPage <= 3 ? i + 1 : currentPage - 2 + i;
                            if (pageNum > totalPages) return null;
                            
                            return (
                                <Button
                                    key={pageNum}
                                    size="sm"
                                    variant={currentPage === pageNum ? 'solid' : 'outline'}
                                    colorScheme={currentPage === pageNum ? 'blue' : 'gray'}
                                    onClick={() => setCurrentPage(pageNum)}
                                >
                                    {pageNum}
                                </Button>
                            );
                        })}
                        
                        <Button
                            size="sm"
                            onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
                            isDisabled={currentPage === totalPages}
                        >
                            Next
                        </Button>
                    </HStack>
                )}

                {/* Quest Details Modal */}
                <Modal isOpen={isOpen} onClose={onClose} size="xl">
                    <ModalOverlay />
                    <ModalContent>
                        <ModalHeader>
                            {selectedQuest?.name}
                        </ModalHeader>
                        <ModalCloseButton />
                        <ModalBody>
                            {selectedQuest && (
                                <VStack spacing={4} align="stretch">
                                    <Box>
                                        <Text fontWeight="bold" mb={2}>Overview</Text>
                                        <Text>{selectedQuest.overview || 'No overview available'}</Text>
                                    </Box>
                                    
                                    <Grid templateColumns="repeat(2, 1fr)" gap={4}>
                                        <Box>
                                            <Text fontWeight="bold" mb={2}>Quest Details</Text>
                                            <VStack align="stretch" spacing={2}>
                                                <HStack justify="space-between">
                                                    <Text fontSize="sm">Level:</Text>
                                                    <Badge colorScheme="blue">Level {selectedQuest.level}</Badge>
                                                </HStack>
                                                <HStack justify="space-between">
                                                    <Text fontSize="sm">Duration:</Text>
                                                    <Text fontSize="sm">{selectedQuest.duration?.name || 'Unknown'}</Text>
                                                </HStack>
                                                <HStack justify="space-between">
                                                    <Text fontSize="sm">Patron:</Text>
                                                    <Text fontSize="sm">{selectedQuest.patron?.name || 'Unknown'}</Text>
                                                </HStack>
                                            </VStack>
                                        </Box>
                                        
                                        <Box>
                                            <Text fontWeight="bold" mb={2}>Adventure Pack</Text>
                                            <Text fontSize="sm">
                                                {selectedQuest.adventure_pack?.name || 'Free to Play'}
                                            </Text>
                                            
                                            <Text fontWeight="bold" mb={2} mt={4}>Difficulties</Text>
                                            <VStack align="stretch" spacing={1}>
                                                {selectedQuest.difficulties?.map(difficulty => (
                                                    <Badge
                                                        key={difficulty.id}
                                                        colorScheme={getDifficultyColor(difficulty.name)}
                                                        width="fit-content"
                                                    >
                                                        {difficulty.name}
                                                    </Badge>
                                                )) || <Text fontSize="sm" color="gray.400">None configured</Text>}
                                            </VStack>
                                        </Box>
                                    </Grid>
                                </VStack>
                            )}
                        </ModalBody>
                        <ModalFooter>
                            <Button variant="outline" mr={3} onClick={onClose}>
                                Close
                            </Button>
                            <Button colorScheme="blue" isDisabled>
                                Edit Quest
                            </Button>
                        </ModalFooter>
                    </ModalContent>
                </Modal>
            </VStack>
        </AuthenticatedLayout>
    );
}
