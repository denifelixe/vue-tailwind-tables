<script setup>
    import TableWithStackedColumnOnMobile from "./TableWithStackedColumnOnMobile.vue";

    //Audience Table
        let somethingTableIsLoaded = ref(false);
        let showSomethingTableFromDate = ref();
        let showSomethingTableToDate = ref();
        let somethingTableColumn = ref([
            {
				name: 'Email',
				attributeName: 'email',
				isBold: true,
				isStackedColumn: true,
			},
			{
				name: 'Created At',
				attributeName: 'createdAt',
				showOnlyWhenScreenIsEqualOrGreaterThan: 'sm',
			},
			{
				name: 'Deleted At',
				attributeName: 'deletedAt',
				showOnlyWhenScreenIsEqualOrGreaterThan: 'sm',
			}
        ]);
        let audienceTableData = ref();
        let audienceTablePagination = ref();
        let audienceTableCurrentPage = ref();

        function setSomethingTable() {
            window.axios.get('/webapi/audiences-table', {
                params: {
                    showAudienceTableFromDate: showAudienceTableFromDate.value,
                    showAudienceTableToDate: showAudienceTableToDate.value,
                    audienceTableColumn: audienceTableColumn.value,
                    page: audienceTableCurrentPage.value ? audienceTableCurrentPage.value : 1,
                }
            }).then((response) => {

                showAudienceTableFromDate.value = response.data.showAudienceTableFromDate;
                showAudienceTableToDate.value = response.data.showAudienceTableToDate;

                audienceTableData.value = response.data.audiencesPaginated.data.map((item) => {
                    return {
                        email: item.email,
                        registeredAt: moment(item.created_at).format('DD MMMM YYYY HH:mm:ss (Z)'),
                        unregisteredAt: item.deleted_at ? moment(item.deleted_at).format('DD MMMM YYYY HH:mm:ss (Z)') : '-',
                    }
                });

                audienceTablePagination.value = {
                    currentPage: response.data.audiencesPaginated.current_page,
                    firstPageUrl: response.data.audiencesPaginated.first_page_url,
                    from: response.data.audiencesPaginated.from,
                    lastPage: response.data.audiencesPaginated.last_page,
                    lastPageUrl: response.data.audiencesPaginated.last_page_url,
                    links: response.data.audiencesPaginated.links.slice(1, -1).map((item) => {
                        return {
                            active: item.active,
                            label: item.label,
                            url: item.url,
                        }
                    }),
                    nextPageUrl: response.data.audiencesPaginated.next_page_url,
                    path: response.data.audiencesPaginated.path,
                    perPage: response.data.audiencesPaginated.per_page,
                    prevPageUrl: response.data.audiencesPaginated.prev_page_url,
                    to: response.data.audiencesPaginated.to,
                    total: response.data.audiencesPaginated.total,
                }

                audienceTableIsLoaded.value = true;
            })
        }

        function updateAudienceTableColumn(audienceTableColumn) {
            audienceTableColumn.value = audienceTableColumn;
            setAudienceTable();
        }

        function updateAudienceTablePage(page) {
            audienceTableCurrentPage.value = page;
            setAudienceTable();
        }
    //
</script>

<template>
    <div class="min-h-[500px]">
        <Transition>
            <TableWithStackedColumnOnMobile v-if="audienceTableIsLoaded" :tableColumn="audienceTableColumn" :tableData="audienceTableData" :tablePagination="audienceTablePagination"
                @update:tableColumn="updateAudienceTableColumn"
                @update:tablePage="updateAudienceTablePage"
            />
        </Transition>
    </div>

    <button @click="setSomethingTable">Set Something Table</button>
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