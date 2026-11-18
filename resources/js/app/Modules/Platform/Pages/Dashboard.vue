
<script setup lang="ts">
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n'; 

const { t, locale } = useI18n();

interface Horse {
    id: number
    name: string
    breed: string
    age: number
    status: string
    owner: string
}

const drawer = ref(true)
const activeTab = ref('overview')
const search = ref('')
const dialog = ref(false)
const snackbar = ref(false)
const snackbarMessage = ref('')

const newHorse = ref({
    name: '',
    breed: '',
    owner: '',
})

const horses = ref<Horse[]>([
    { id: 1, name: 'Apollo', breed: 'Hannoveraner', age: 8, status: 'Active', owner: 'Anna Müller' },
    { id: 2, name: 'Bella', breed: 'Oldenburger', age: 12, status: 'Active', owner: 'Thomas Weber' },
    { id: 3, name: 'Charlie', breed: 'Friese', age: 6, status: 'Resting', owner: 'Anna Müller' },
    { id: 4, name: 'Daisy', breed: 'Westfale', age: 10, status: 'Active', owner: 'Lisa Schmidt' },
    { id: 5, name: 'Elliot', breed: 'Araber', age: 5, status: 'Inactive', owner: 'Michael Fischer' },
])

const headers = [
    { title: 'Horse', key: 'name' },
    { title: 'Breed', key: 'breed' },
    { title: 'Age', key: 'age' },
    { title: 'Owner', key: 'owner' },
    { title: 'Status', key: 'status' },
]

const filteredHorses = computed(() => {
    const query = search.value.toLowerCase()

    if (!query) return horses.value

    return horses.value.filter((horse) =>
        [horse.name, horse.breed, horse.owner, horse.status]
            .some((value) => value.toLowerCase().includes(query)),
    )
})

function showMessage(message: string) {
    snackbarMessage.value = message
    snackbar.value = true
}

function addHorse() {
    if (!newHorse.value.name.trim()) return

    horses.value.push({
        id: Math.max(0, ...horses.value.map((horse) => horse.id)) + 1,
        name: newHorse.value.name,
        breed: newHorse.value.breed || 'Unknown',
        age: 0,
        owner: newHorse.value.owner || 'Unassigned',
        status: 'Active',
    })

    dialog.value = false
    newHorse.value = { name: '', breed: '', owner: '' }

    showMessage('Horse successfully added.')
}
</script>

<template>
    <v-app>
        <v-layout class="rounded rounded-md">
            <!-- Navigation -->
            <v-navigation-drawer
                v-model="drawer"
                :permanent="$vuetify.display.mdAndUp"
                width="260"
            >
                <div class="pa-5">
                    <div class="text-h5 font-weight-bold">
                        {{ t('application.name') }}
                    </div>
                    <div>
                        Active locale: {{ locale }}
                    </div>
                    <div class="text-caption text-medium-emphasis">
                        Development Playground
                    </div>
                </div>

                <v-divider />

                <v-list nav class="pa-3">
                    <v-list-subheader>GENERAL</v-list-subheader>

                    <v-list-item
                        title="Dashboard"
                        value="dashboard"
                        active
                        rounded="lg"
                    />

                    <v-list-item
                        title="Horses"
                        value="horses"
                        rounded="lg"
                        @click="showMessage('Horses module is not implemented yet.')"
                    />

                    <v-list-item
                        title="Stable"
                        value="stable"
                        rounded="lg"
                        @click="showMessage('Stable module is not implemented yet.')"
                    />

                    <v-list-item
                        title="Finance"
                        value="finance"
                        rounded="lg"
                        @click="showMessage('Finance module is not implemented yet.')"
                    />

                    <v-divider class="my-3" />

                    <v-list-subheader>ADMINISTRATION</v-list-subheader>

                    <v-list-item
                        title="Settings"
                        value="settings"
                        rounded="lg"
                        @click="showMessage('Settings are not implemented yet.')"
                    />
                </v-list>

                <template #append>
                    <v-divider />
                    <div class="pa-4">
                        <div class="text-caption text-medium-emphasis">
                            StableManager
                        </div>
                        <div class="text-body-2 font-weight-medium">
                            Development Environment
                        </div>
                    </div>
                </template>
            </v-navigation-drawer>

            <!-- Main content -->
            <v-main>
                <!-- App bar -->
                <v-app-bar flat border="bottom">
                    <v-app-bar-nav-icon
                        class="d-md-none"
                        @click="drawer = !drawer"
                    />

                    <v-app-bar-title>
                        Dashboard
                    </v-app-bar-title>

                    <v-spacer />

                    <v-btn
                        variant="text"
                        class="mr-2"
                        @click="showMessage('No new notifications.')"
                    >
                        Notifications
                    </v-btn>

                    <v-avatar color="primary" size="36" class="mr-4">
                        <span class="text-caption">AM</span>
                    </v-avatar>
                </v-app-bar>

                <v-container fluid class="pa-4 pa-md-6">
                    <!-- Page heading -->
                    <div class="d-flex flex-wrap align-center justify-space-between ga-3 mb-6">
                        <div>
                            <div class="text-h4 font-weight-bold">
                                Good morning!
                            </div>
                            <div class="text-body-2 text-medium-emphasis mt-1">
                                Welcome to your StableManager dashboard.
                            </div>
                        </div>

                        <v-btn
                            color="primary"
                            prepend-icon="mdi-plus"
                            @click="dialog = true"
                        >
                            Add horse
                        </v-btn>
                    </div>

                    <!-- Statistics -->
                    <v-row>
                        <v-col cols="12" sm="6" lg="3">
                            <v-card rounded="lg" border flat>
                                <v-card-text>
                                    <div class="text-body-2 text-medium-emphasis">
                                        Total horses
                                    </div>
                                    <div class="text-h4 font-weight-bold mt-2">
                                        {{ horses.length }}
                                    </div>
                                    <v-chip color="success" size="small" class="mt-3">
                                        +2 this month
                                    </v-chip>
                                </v-card-text>
                            </v-card>
                        </v-col>

                        <v-col cols="12" sm="6" lg="3">
                            <v-card rounded="lg" border flat>
                                <v-card-text>
                                    <div class="text-body-2 text-medium-emphasis">
                                        Active horses
                                    </div>
                                    <div class="text-h4 font-weight-bold mt-2">
                                        {{ horses.filter(h => h.status === 'Active').length }}
                                    </div>
                                    <v-chip color="success" size="small" class="mt-3">
                                        All systems normal
                                    </v-chip>
                                </v-card-text>
                            </v-card>
                        </v-col>

                        <v-col cols="12" sm="6" lg="3">
                            <v-card rounded="lg" border flat>
                                <v-card-text>
                                    <div class="text-body-2 text-medium-emphasis">
                                        Monthly revenue
                                    </div>
                                    <div class="text-h4 font-weight-bold mt-2">
                                        € 8,450
                                    </div>
                                    <v-chip color="success" size="small" class="mt-3">
                                        +8.5%
                                    </v-chip>
                                </v-card-text>
                            </v-card>
                        </v-col>

                        <v-col cols="12" sm="6" lg="3">
                            <v-card rounded="lg" border flat>
                                <v-card-text>
                                    <div class="text-body-2 text-medium-emphasis">
                                        Open tasks
                                    </div>
                                    <div class="text-h4 font-weight-bold mt-2">
                                        7
                                    </div>
                                    <v-chip color="warning" size="small" class="mt-3">
                                        Requires attention
                                    </v-chip>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>

                    <!-- Alerts -->
                    <v-alert
                        type="info"
                        variant="tonal"
                        rounded="lg"
                        class="my-5"
                        title="Welcome to the UI Playground"
                        text="This page demonstrates common Material Design components using Vuetify."
                        closable
                    />

                    <!-- Tabs -->
                    <v-card rounded="lg" border flat class="mb-5">
                        <v-tabs v-model="activeTab" color="primary">
                            <v-tab value="overview">Overview</v-tab>
                            <v-tab value="activity">Activity</v-tab>
                            <v-tab value="settings">Settings</v-tab>
                        </v-tabs>

                        <v-divider />

                        <v-card-text>
                            <div v-if="activeTab === 'overview'">
                                <div class="text-h6 mb-2">Overview</div>
                                <div class="text-body-2 text-medium-emphasis">
                                    This is an example of a tabbed interface.
                                </div>
                            </div>

                            <div v-else-if="activeTab === 'activity'">
                                <div class="text-h6 mb-2">Recent activity</div>
                                <div class="text-body-2 text-medium-emphasis">
                                    Apollo was added to the stable recently.
                                </div>
                            </div>

                            <div v-else>
                                <div class="text-h6 mb-2">Dashboard settings</div>
                                <div class="text-body-2 text-medium-emphasis">
                                    Settings for this dashboard will be available here.
                                </div>
                            </div>
                        </v-card-text>
                    </v-card>

                    <!-- Data table -->
                    <v-card rounded="lg" border flat class="mb-5">
                        <v-card-title class="d-flex flex-wrap align-center ga-3 pa-4">
                            <span class="text-h6">Horses</span>
                            <v-spacer />
                            <v-text-field
                                v-model="search"
                                label="Search horses"
                                density="compact"
                                variant="outlined"
                                hide-details
                                clearable
                                max-width="280"
                            />
                        </v-card-title>

                        <v-divider />

                        <v-data-table
                            :headers="headers"
                            :items="filteredHorses"
                            item-value="id"
                            hover
                            items-per-page="5"
                        >
                            <template #item.status="{ item }">
                                <v-chip
                                    :color="item.status === 'Active'
                                        ? 'success'
                                        : item.status === 'Resting'
                                            ? 'warning'
                                            : 'default'"
                                    size="small"
                                >
                                    {{ item.status }}
                                </v-chip>
                            </template>

                            <template #no-data>
                                <div class="pa-6 text-medium-emphasis">
                                    No horses found.
                                </div>
                            </template>
                        </v-data-table>
                    </v-card>

                    <!-- Form components -->
                    <v-row>
                        <v-col cols="12" lg="6">
                            <v-card rounded="lg" border flat height="100%">
                                <v-card-title class="pa-4">
                                    Form elements
                                </v-card-title>

                                <v-divider />

                                <v-card-text class="pa-4">
                                    <div class="text-subtitle-2 mb-3">
                                        Text fields
                                    </div>

                                    <v-text-field
                                        label="Full name"
                                        placeholder="Enter your name"
                                        variant="outlined"
                                        class="mb-2"
                                    />

                                    <v-text-field
                                        label="Email address"
                                        placeholder="name@example.com"
                                        type="email"
                                        variant="outlined"
                                        class="mb-2"
                                    />

                                    <v-select
                                        label="Select a role"
                                        :items="['Administrator', 'Manager', 'Employee']"
                                        variant="outlined"
                                        class="mb-2"
                                    />

                                    <v-textarea
                                        label="Description"
                                        placeholder="Enter a description..."
                                        variant="outlined"
                                        rows="3"
                                    />

                                    <v-checkbox
                                        label="Accept terms and conditions"
                                        color="primary"
                                        hide-details
                                    />

                                    <v-switch
                                        label="Enable notifications"
                                        color="primary"
                                        hide-details
                                    />

                                    <v-btn
                                        color="primary"
                                        class="mt-4"
                                        @click="showMessage('Form submitted successfully.')"
                                    >
                                        Submit form
                                    </v-btn>
                                </v-card-text>
                            </v-card>
                        </v-col>

                        <v-col cols="12" lg="6">
                            <v-card rounded="lg" border flat height="100%">
                                <v-card-title class="pa-4">
                                    Buttons & status indicators
                                </v-card-title>

                                <v-divider />

                                <v-card-text class="pa-4">
                                    <div class="text-subtitle-2 mb-3">
                                        Button variants
                                    </div>

                                    <div class="d-flex flex-wrap ga-2 mb-6">
                                        <v-btn color="primary">Elevated</v-btn>
                                        <v-btn color="primary" variant="flat">Flat</v-btn>
                                        <v-btn color="primary" variant="tonal">Tonal</v-btn>
                                        <v-btn color="primary" variant="outlined">Outlined</v-btn>
                                        <v-btn color="primary" variant="text">Text</v-btn>
                                    </div>

                                    <v-divider class="mb-5" />

                                    <div class="text-subtitle-2 mb-3">
                                        Chips
                                    </div>

                                    <div class="d-flex flex-wrap ga-2 mb-6">
                                        <v-chip color="success">Active</v-chip>
                                        <v-chip color="warning">Pending</v-chip>
                                        <v-chip color="error">Overdue</v-chip>
                                        <v-chip color="info">Information</v-chip>
                                        <v-chip variant="outlined">Default</v-chip>
                                    </div>

                                    <v-divider class="mb-5" />

                                    <div class="text-subtitle-2 mb-3">
                                        Alerts
                                    </div>

                                    <v-alert
                                        type="success"
                                        variant="tonal"
                                        density="compact"
                                        class="mb-2"
                                    >
                                        Operation completed successfully.
                                    </v-alert>

                                    <v-alert
                                        type="warning"
                                        variant="tonal"
                                        density="compact"
                                    >
                                        Please check the entered information.
                                    </v-alert>

                                    <v-divider class="my-5" />

                                    <div class="text-subtitle-2 mb-3">
                                        Progress
                                    </div>

                                    <div class="text-caption mb-2">
                                        Stable capacity: 75%
                                    </div>

                                    <v-progress-linear
                                        :model-value="75"
                                        color="primary"
                                        height="10"
                                        rounded
                                    />
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>

                    <!-- Footer -->
                    <div class="text-caption text-medium-emphasis text-center mt-8 mb-4">
                        StableManager · UI Playground · Development
                    </div>
                </v-container>
            </v-main>
        </v-layout>

        <!-- Add horse dialog -->
        <v-dialog v-model="dialog" max-width="500">
            <v-card rounded="lg">
                <v-card-title class="pa-5">
                    Add a new horse
                </v-card-title>

                <v-divider />

                <v-card-text class="pa-5">
                    <v-text-field
                        v-model="newHorse.name"
                        label="Horse name"
                        variant="outlined"
                        autofocus
                        required
                    />

                    <v-text-field
                        v-model="newHorse.breed"
                        label="Breed"
                        variant="outlined"
                    />

                    <v-text-field
                        v-model="newHorse.owner"
                        label="Owner"
                        variant="outlined"
                    />
                </v-card-text>

                <v-divider />

                <v-card-actions class="pa-4">
                    <v-spacer />
                    <v-btn
                        variant="text"
                        @click="dialog = false"
                    >
                        Cancel
                    </v-btn>

                    <v-btn
                        color="primary"
                        :disabled="!newHorse.name.trim()"
                        @click="addHorse"
                    >
                        Save horse
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Snackbar -->
        <v-snackbar v-model="snackbar" location="bottom end">
            {{ snackbarMessage }}

            <template #actions>
                <v-btn
                    variant="text"
                    @click="snackbar = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>
