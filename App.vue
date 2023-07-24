<script setup>
    import { onMounted, ref } from 'vue';
    import moment from 'moment';

    import TableWithStackedColumnOnMobile from "./TableWithStackedColumnOnMobile.vue";

    //Audience Table
        let somethingTableIsLoaded = ref(false);
        // let showSomethingTableFromDate = ref();
        // let showSomethingTableToDate = ref();
        let somethingTableColumn = ref([
            {
				name: 'Email',
				attributeName: 'email',
				isBold: true,
				isStackedColumn: true,
                isFiltering: false,
                filters: {
                    email: ''
                }
			},
			{
				name: 'Registered At',
				attributeName: 'registeredAt',
				showOnlyWhenScreenIsEqualOrGreaterThan: 'sm',
                showFilter: false,
                isFiltering: false,
                filters: {
                    from: '',
                    to: ''
                }
			},
			{
				name: 'Unregistered At',
				attributeName: 'unregisteredAt',
				showOnlyWhenScreenIsEqualOrGreaterThan: 'sm',
                showFilter: false,
                isFiltering: false,
                filters: {
                    from: '',
                    to: ''
                }
			}
        ]);
        let somethingTableData = ref();
        let somethingTablePagination = ref();
        let somethingTableCurrentPage = ref();

        //Filters
            watch( //email
                () => somethingTableColumn.value[0].filters.email, 
                (newValue, oldValue) => {
                    somethingTableColumn.value[0].isFiltering = newValue ? true : false;
                }
            );
            watch( //registeredAt
                () => somethingTableColumn.value[1].filters, 
                (newValue, oldValue) => {
                    somethingTableColumn.value[1].isFiltering = newValue.from && newValue.to ? true : false;
                },
                { deep: true }
            );
            watch( //unregisteredAt
                () => somethingTableColumn.value[2].filters, 
                (newValue, oldValue) => {
                    somethingTableColumn.value[2].isFiltering = newValue.from && newValue.to ? true : false;
                },
                { deep: true }
            );
        //

        function setSomethingTable() {

            somethingTableIsLoaded.value = false;

            window.axios.get('/webapi/somethings-table', {
                params: {
                    // showSomethingTableFromDate: showSomethingTableFromDate.value ? moment(showSomethingTableFromDate.value).toISOString(true) : moment().subtract(31, 'days').startOf('day').toISOString(true),
                    // showSomethingTableToDate: showSomethingTableToDate.value ? moment(showSomethingTableToDate.value).toISOString(true) : moment().startOf('day').toISOString(true),
                    somethingTableColumn: somethingTableColumn.value,
                    page: somethingTableCurrentPage.value ? somethingTableCurrentPage.value : 1,
                }
            }).then((response) => {

                // showSomethingTableFromDate.value = response.data.showSomethingTableFromDate;
                // showSomethingTableToDate.value = response.data.showSomethingTableToDate;

                somethingTableData.value = response.data.somethingsPaginated.data.map((item) => {
                    return {
                        email: item.email,
                        registeredAt: moment(item.created_at).format('DD MMMM YYYY HH:mm:ss (Z)'), //turn GMT+0 from server into client's timezone
                        unregisteredAt: item.deleted_at ? moment(item.deleted_at).format('DD MMMM YYYY HH:mm:ss (Z)') : '-', //turn GMT+0 from server into client's timezone
                    }
                });

                somethingTablePagination.value = {
                    currentPage: response.data.somethingsPaginated.current_page,
                    firstPageUrl: response.data.somethingsPaginated.first_page_url,
                    from: response.data.somethingsPaginated.from,
                    lastPage: response.data.somethingsPaginated.last_page,
                    lastPageUrl: response.data.somethingsPaginated.last_page_url,
                    links: response.data.somethingsPaginated.links.slice(1, -1).map((item) => {
                        return {
                            active: item.active,
                            label: item.label,
                            url: item.url,
                        }
                    }),
                    nextPageUrl: response.data.somethingsPaginated.next_page_url,
                    path: response.data.somethingsPaginated.path,
                    perPage: response.data.somethingsPaginated.per_page,
                    prevPageUrl: response.data.somethingsPaginated.prev_page_url,
                    to: response.data.somethingsPaginated.to,
                    total: response.data.somethingsPaginated.total,
                }

                somethingTableIsLoaded.value = true;

            })
        }

        function updateSomethingTableColumn(somethingTableColumn) {
            somethingTableColumn.value = somethingTableColumn;
            setSomethingTable();
        }

        function updateSomethingTablePage(page) {
            somethingTableCurrentPage.value = page;
            setSomethingTable();
        }

        onMounted(() => {
            setSomethingTable();
        });
    //
</script>

<template>

    <!-- Filters -->
        <div class="flex flex-col gap-5 mb-5">
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                <div class="mt-2">
                    <input type="email" id="email" v-model="somethingTableColumn[0].filters.email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Search email...">
                </div>
            </div>
            
            <div class="inline-flex gap-2 items-center">
                <label for="email" class="text-sm font-medium leading-6 text-gray-900">Registered At:</label>
                <div v-if="somethingTableColumn[1].showFilter" class="inline-flex gap-2">
                    <DatePicker v-model="somethingTableColumn[1].filters.from">From Date</DatePicker> 
                    <DatePicker v-model="somethingTableColumn[1].filters.to" :disabledDatesBefore="somethingTableColumn[1].filters.from">To Date</DatePicker>
                </div>
                <div v-else>
                    <span class="text-sm font-medium text-gray-900">No Filter</span>
                </div>
                <button type="button" v-if="!somethingTableColumn[1].showFilter" @click="somethingTableColumn[1].showFilter = true;" class="inline-block rounded-md bg-blue-500 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 mr-1">Add</button>
                <button type="button" v-if="somethingTableColumn[1].showFilter" @click="somethingTableColumn[1].showFilter = false; somethingTableColumn[1].isFiltering = false; somethingTableColumn[1].filters.from = ''; somethingTableColumn[1].filters.to = ''" class="inline-block rounded-md bg-red-500 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 mr-1">Clear</button>
            </div>
            
            <div class="inline-flex gap-2 items-center">
                <label for="email" class="text-sm font-medium leading-6 text-gray-900">Unregistered At:</label>
                <div v-if="somethingTableColumn[2].showFilter" class="inline-flex gap-2">
                    <DatePicker v-model="somethingTableColumn[2].filters.from">From Date</DatePicker> 
                    <DatePicker v-model="somethingTableColumn[2].filters.to" :disabledDatesBefore="somethingTableColumn[2].filters.from">To Date</DatePicker>
                </div>
                <div v-else>
                    <span class="text-sm font-medium text-gray-900">No Filter</span>
                </div>
                <button type="button" v-if="!somethingTableColumn[2].showFilter" @click="somethingTableColumn[2].showFilter = true;" class="inline-block rounded-md bg-blue-500 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 mr-1">Add</button>
                <button type="button" v-if="somethingTableColumn[2].showFilter" @click="somethingTableColumn[2].showFilter = false; somethingTableColumn[2].isFiltering = false; somethingTableColumn[2].filters.from = ''; somethingTableColumn[2].filters.to = ''" class="inline-block rounded-md bg-red-500 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 mr-1">Clear</button>
            </div>
            
            <div>
                <button type="button" @click="setAudienceTable" class="inline-block rounded-md bg-green-500 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-green-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 mr-1">Apply</button>
            </div>
        </div>
    <!--  -->

    <button @click="setSomethingTable">Set Something Table</button>

    <div class="min-h-[500px]">
        <Transition>
            <TableWithStackedColumnOnMobile v-if="somethingTableIsLoaded" :tableColumn="somethingTableColumn" :tableData="somethingTableData" :tablePagination="somethingTablePagination"
                @update:tableColumn="updateSomethingTableColumn"
                @update:tablePage="updateSomethingTablePage"
            />
        </Transition>
    </div>

</template>

<style>
    .v-enter-active,
    .v-leave-active {
        transition: opacity 3s ease;
    }
    
    .v-enter-from,
    .v-leave-to {
        opacity: 0;
    }
</style>