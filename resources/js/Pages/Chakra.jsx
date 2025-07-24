import React from 'react';
import {
    Accordion, AccordionItem, AccordionButton, AccordionPanel, AccordionIcon,
    Alert, AlertIcon, AlertTitle, AlertDescription,
    Avatar,
    Badge,
    Box,
    Breadcrumb, BreadcrumbItem, BreadcrumbLink,
    Button,
    Card, CardHeader, CardBody, CardFooter,
    Checkbox,
    CircularProgress,
    Code,
    Container,
    Divider,
    Drawer, DrawerBody, DrawerFooter, DrawerHeader, DrawerOverlay, DrawerContent, DrawerCloseButton, useDisclosure,
    Flex,
    FormControl, FormLabel, FormErrorMessage, FormHelperText,
    Grid, GridItem,
    Heading,
    Icon,
    IconButton,
    Image,
    Input,
    Link,
    List, ListItem, ListIcon,
    Menu, MenuButton, MenuList, MenuItem,
    Modal, ModalOverlay, ModalContent, ModalHeader, ModalFooter, ModalBody, ModalCloseButton,
    NumberInput, NumberInputField, NumberInputStepper, NumberIncrementStepper, NumberDecrementStepper,
    PinInput, PinInputField,
    Popover, PopoverTrigger, PopoverContent, PopoverHeader, PopoverBody, PopoverArrow, PopoverCloseButton,
    Progress,
    Radio, RadioGroup,
    Select,
    Skeleton,
    Slider, SliderTrack, SliderFilledTrack, SliderThumb,
    Spinner,
    Stat, StatLabel, StatNumber, StatHelpText, StatArrow, StatGroup,
    Switch,
    Table, Thead, Tbody, Tfoot, Tr, Th, Td, TableCaption, TableContainer,
    Tabs, TabList, TabPanels, Tab, TabPanel,
    Tag,
    Text,
    Textarea,
    Tooltip,
    Kbd,
    Stack,
    Wrap, WrapItem
} from '@chakra-ui/react';
import { ChevronRightIcon, CheckCircleIcon, WarningIcon } from '@chakra-ui/icons';

export default function Chakra() {
    const { isOpen: isDrawerOpen, onOpen: onDrawerOpen, onClose: onDrawerClose } = useDisclosure();
    const { isOpen: isModalOpen, onOpen: onModalOpen, onClose: onModalClose } = useDisclosure();

    return (
        <Container maxW="container.xl" p={5}>
            <Heading as="h1" size="xl" mb={5}>Chakra UI Components</Heading>

            <Stack spacing={10}>
                <Box>
                    <Heading as="h2" size="lg" mb={3}>Accordion</Heading>
                    <Accordion>
                        <AccordionItem>
                            <h2>
                                <AccordionButton>
                                    <Box flex="1" textAlign="left">
                                        Section 1 title
                                    </Box>
                                    <AccordionIcon />
                                </AccordionButton>
                            </h2>
                            <AccordionPanel pb={4}>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            </AccordionPanel>
                        </AccordionItem>
                    </Accordion>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Alert</Heading>
                    <Alert status="success">
                        <AlertIcon />
                        Data uploaded to the server. Fire on!
                    </Alert>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Avatar</Heading>
                    <Avatar name="Dan Abrahmov" src="https://bit.ly/dan-abramov" />
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Badge</Heading>
                    <Badge colorScheme="green">Success</Badge>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Breadcrumb</Heading>
                    <Breadcrumb spacing="8px" separator={<ChevronRightIcon color="gray.500" />}>
                        <BreadcrumbItem>
                            <BreadcrumbLink href="#">Home</BreadcrumbLink>
                        </BreadcrumbItem>
                        <BreadcrumbItem>
                            <BreadcrumbLink href="#">Docs</BreadcrumbLink>
                        </BreadcrumbItem>
                    </Breadcrumb>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Button</Heading>
                    <Button colorScheme="blue">Button</Button>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Card</Heading>
                    <Card>
                        <CardHeader>
                            <Heading size='md'>Client Report</Heading>
                        </CardHeader>
                        <CardBody>
                            <Text>View a summary of all your clients over the last month.</Text>
                        </CardBody>
                        <CardFooter>
                            <Button>View here</Button>
                        </CardFooter>
                    </Card>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Checkbox</Heading>
                    <Checkbox defaultChecked>Checkbox</Checkbox>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Circular Progress</Heading>
                    <CircularProgress value={80} />
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Code</Heading>
                    <Code>console.log("Hello World")</Code>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Divider</Heading>
                    <Divider />
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Drawer</Heading>
                    <Button onClick={onDrawerOpen}>Open Drawer</Button>
                    <Drawer isOpen={isDrawerOpen} placement="right" onClose={onDrawerClose}>
                        <DrawerOverlay />
                        <DrawerContent>
                            <DrawerCloseButton />
                            <DrawerHeader>Create your account</DrawerHeader>
                            <DrawerBody>
                                <Input placeholder="Type here..." />
                            </DrawerBody>
                            <DrawerFooter>
                                <Button variant="outline" mr={3} onClick={onDrawerClose}>Cancel</Button>
                                <Button colorScheme="blue">Save</Button>
                            </DrawerFooter>
                        </DrawerContent>
                    </Drawer>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Flex</Heading>
                    <Flex>
                        <Box p="4" bg="red.400">Box 1</Box>
                        <Box p="4" bg="green.400">Box 2</Box>
                    </Flex>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Form Control</Heading>
                    <FormControl>
                        <FormLabel>Email address</FormLabel>
                        <Input type="email" />
                        <FormHelperText>We'll never share your email.</FormHelperText>
                    </FormControl>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Grid</Heading>
                    <Grid templateColumns="repeat(5, 1fr)" gap={6}>
                        <GridItem w="100%" h="10" bg="blue.500" />
                        <GridItem w="100%" h="10" bg="blue.500" />
                    </Grid>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Icon Button</Heading>
                    <IconButton aria-label="Search database" icon={<WarningIcon />} />
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Image</Heading>
                    <Image boxSize="150px" src="https://bit.ly/dan-abramov" alt="Dan Abramov" />
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Link</Heading>
                    <Link color="teal.500" href="#">Chakra UI</Link>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>List</Heading>
                    <List spacing={3}>
                        <ListItem>
                            <ListIcon as={CheckCircleIcon} color="green.500" />
                            Lorem ipsum dolor sit amet
                        </ListItem>
                    </List>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Menu</Heading>
                    <Menu>
                        <MenuButton as={Button}>Actions</MenuButton>
                        <MenuList>
                            <MenuItem>Download</MenuItem>
                            <MenuItem>Create a Copy</MenuItem>
                        </MenuList>
                    </Menu>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Modal</Heading>
                    <Button onClick={onModalOpen}>Open Modal</Button>
                    <Modal isOpen={isModalOpen} onClose={onModalClose}>
                        <ModalOverlay />
                        <ModalContent>
                            <ModalHeader>Modal Title</ModalHeader>
                            <ModalCloseButton />
                            <ModalBody>
                                <Text>Sit nulla est ex deserunt exercitation anim occaecat. Nostrud ullamco deserunt aute id consequat veniam incididunt duis in sint irure nisi.</Text>
                            </ModalBody>
                            <ModalFooter>
                                <Button colorScheme="blue" mr={3} onClick={onModalClose}>Close</Button>
                                <Button variant="ghost">Secondary Action</Button>
                            </ModalFooter>
                        </ModalContent>
                    </Modal>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Number Input</Heading>
                    <NumberInput>
                        <NumberInputField />
                        <NumberInputStepper>
                            <NumberIncrementStepper />
                            <NumberDecrementStepper />
                        </NumberInputStepper>
                    </NumberInput>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Pin Input</Heading>
                    <PinInput>
                        <PinInputField />
                        <PinInputField />
                        <PinInputField />
                    </PinInput>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Popover</Heading>
                    <Popover>
                        <PopoverTrigger>
                            <Button>Trigger</Button>
                        </PopoverTrigger>
                        <PopoverContent>
                            <PopoverArrow />
                            <PopoverCloseButton />
                            <PopoverHeader>Confirmation!</PopoverHeader>
                            <PopoverBody>Are you sure you want to have that milkshake?</PopoverBody>
                        </PopoverContent>
                    </Popover>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Progress</Heading>
                    <Progress value={80} />
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Radio</Heading>
                    <RadioGroup defaultValue="2">
                        <Stack spacing={5} direction="row">
                            <Radio colorScheme="red" value="1">Radio 1</Radio>
                            <Radio colorScheme="green" value="2">Radio 2</Radio>
                        </Stack>
                    </RadioGroup>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Select</Heading>
                    <Select placeholder="Select option">
                        <option value="option1">Option 1</option>
                        <option value="option2">Option 2</option>
                    </Select>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Skeleton</Heading>
                    <Skeleton height="20px" />
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Slider</Heading>
                    <Slider aria-label="slider-ex-1" defaultValue={30}>
                        <SliderTrack>
                            <SliderFilledTrack />
                        </SliderTrack>
                        <SliderThumb />
                    </Slider>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Spinner</Heading>
                    <Spinner />
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Stat</Heading>
                    <StatGroup>
                        <Stat>
                            <StatLabel>Sent</StatLabel>
                            <StatNumber>345,670</StatNumber>
                            <StatHelpText>
                                <StatArrow type="increase" />
                                23.36%
                            </StatHelpText>
                        </Stat>
                    </StatGroup>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Switch</Heading>
                    <Switch />
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Table</Heading>
                    <TableContainer>
                        <Table variant="simple">
                            <TableCaption>Imperial to metric conversion factors</TableCaption>
                            <Thead>
                                <Tr>
                                    <Th>To convert</Th>
                                    <Th>into</Th>
                                    <Th isNumeric>multiply by</Th>
                                </Tr>
                            </Thead>
                            <Tbody>
                                <Tr>
                                    <Td>inches</Td>
                                    <Td>millimetres (mm)</Td>
                                    <Td isNumeric>25.4</Td>
                                </Tr>
                            </Tbody>
                        </Table>
                    </TableContainer>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Tabs</Heading>
                    <Tabs>
                        <TabList>
                            <Tab>One</Tab>
                            <Tab>Two</Tab>
                        </TabList>
                        <TabPanels>
                            <TabPanel>
                                <p>one!</p>
                            </TabPanel>
                            <TabPanel>
                                <p>two!</p>
                            </TabPanel>
                        </TabPanels>
                    </Tabs>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Tag</Heading>
                    <Tag>Sample Tag</Tag>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Textarea</Heading>
                    <Textarea placeholder="Here is a sample placeholder" />
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Tooltip</Heading>
                    <Tooltip label="Hey, I'm a tooltip!">
                        <Button>Hover me</Button>
                    </Tooltip>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Kbd</Heading>
                    <Kbd>ctrl</Kbd> + <Kbd>c</Kbd>
                </Box>

                <Box>
                    <Heading as="h2" size="lg" mb={3}>Wrap</Heading>
                    <Wrap>
                        <WrapItem>
                            <Badge>Badge 1</Badge>
                        </WrapItem>
                        <WrapItem>
                            <Badge colorScheme="green">Badge 2</Badge>
                        </WrapItem>
                    </Wrap>
                </Box>
            </Stack>
        </Container>
    );
}
