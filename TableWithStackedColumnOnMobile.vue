<script setup>
	import { computed } from 'vue';

	const props = defineProps([
		'tableColumn',
		'tableData',
		'tablePagination',
	])

	const emit = defineEmits([
		'update:tableColumn',
		'update:tablePage',
	])

	const tableColumn = computed(() => {
		if (props.tableColumn) {
            return props.tableColumn;
        }

        //Example
        return [
			{
				name: 'Email',
				attributeName: 'email',
				isBold: true,
				isStackedColumn: true,
				isSort: false,
			},
			{
				name: 'Registered At',
				attributeName: 'registeredAt',
				showOnlyWhenScreenIsEqualOrGreaterThan: 'sm',
				isStacked: true,
				isSort: true,
				sortPriority: 1,
				sortOrientation: 'ASC', //ASC or DESC
			},
			{
				name: 'Unregistered At',
				attributeName: 'unregisteredAt',
				showOnlyWhenScreenIsEqualOrGreaterThan: 'sm',
				isStacked: true,
				isSort: true,
				sortPriority: 2,
				sortOrientation: 'DESC', //ASC or DESC
			}
		];
	});

	const tableData = computed(() => {
		if (props.tableData) {
			return props.tableData;
		}

		//Example
		return [
			{
				email: 'deni.victoria@gmail.com',
				registeredAt: '2021-08-01 00:00:00',
				unregisteredAt: '2022-08-01 00:00:00',
			},
			{
				email: 'denibbandroid@gmail.com',
				registeredAt: '2021-08-01 00:00:00',
				unregisteredAt: '2022-08-01 00:00:00',
			},
		];
	});

	const tablePagination = computed(() => {
		if (props.tablePagination) {
			return props.tablePagination;
		}

		//Example
		return {
			currentPage: 1,
			firstPageUrl: "http://admin.zenadigital.test/dashboard?page=1",
			from: 1,
			lastPage: 54,
			lastPageUrl: "http://admin.zenadigital.test/dashboard?page=54",
			links: [
				{
					url: "http://admin.zenadigital.test/dashboard?page=1",
					label: "1",
					active: true
				},
				{
					url: "http://admin.zenadigital.test/dashboard?page=2",
					label: "2",
					active: false
				},
				{
					url: "http://admin.zenadigital.test/dashboard?page=3",
					label: "3",
					active: false
				},
				{
					url: "http://admin.zenadigital.test/dashboard?page=4",
					label: "4",
					active: false
				},
				{
					url: "http://admin.zenadigital.test/dashboard?page=5",
					label: "5",
					active: false
				},
				{
					url: "http://admin.zenadigital.test/dashboard?page=6",
					label: "6",
					active: false
				},
				{
					url: "http://admin.zenadigital.test/dashboard?page=7",
					label: "7",
					active: false
				},
				{
					url: "http://admin.zenadigital.test/dashboard?page=8",
					label: "8",
					active: false
				},
				{
					url: "http://admin.zenadigital.test/dashboard?page=9",
					label: "9",
					active: false
				},
				{
					url: "http://admin.zenadigital.test/dashboard?page=10",
					label: "10",
					active: false
				},
				{
					url: null,
					label: "...",
					active: false
				},
				{
					url: "http://admin.zenadigital.test/dashboard?page=53",
					label: "53",
					active: false
				},
				{
					url: "http://admin.zenadigital.test/dashboard?page=54",
					label: "54",
					active: false
				},
			],
			nextPageUrl: "http://admin.zenadigital.test/dashboard?page=2",
			path: "http://admin.zenadigital.test/dashboard",
			perPage: 10,
			prevPageUrl: null,
			to: 10,
			total: 540
		};
	});

	function sort(column, event) {
		
		if (!event.shiftKey) {
			
			tableColumn.value.forEach((item) => {
				if (item.attributeName !== column.attributeName) {
					item.isSort = false;
					item.sortPriority = null;
					item.sortOrientation = null;
				}
			});

			column.isSort = true;
			column.sortPriority = 1;
	
		} else {

			if (!column.sortPriority) {
				let largestSortPriority = 0;
				tableColumn.value.forEach((item) => {
					if (item.sortPriority > largestSortPriority) {
						largestSortPriority = item.sortPriority;
					}
				});

				column.isSort = true;
				column.sortPriority = largestSortPriority + 1;
			}

		}

		if (column.sortOrientation === 'ASC') {
			column.sortOrientation = 'DESC';
		} else {
			column.sortOrientation = 'ASC';
		}

		emit('update:tableColumn', tableColumn.value);

	}
</script>

<template>
	<!-- This component is from TailwindUI -->
    <!-- https://tailwindui.com/components/application-ui/lists/tables#component-e56f750c63d4e53a24f5f0bf9fd7b52a -->
	<div class="-mx-4 sm:-mx-0">
		<table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
			<thead>
				<tr class="!bg-transparent text-gray-900 dark:!text-gray-400 dark:!bg-transparent">
					<th v-for="column in tableColumn" scope="col" class="py-4 px-4 text-left text-sm font-semibold sm:pl-0 cursor-pointer"
						:class="{
							'hidden sm:table-cell': column.showOnlyWhenScreenIsEqualOrGreaterThan === 'sm',
							'hidden md:table-cell': column.showOnlyWhenScreenIsEqualOrGreaterThan === 'md',
							'hidden lg:table-cell': column.showOnlyWhenScreenIsEqualOrGreaterThan === 'lg',
							'hidden xl:table-cell': column.showOnlyWhenScreenIsEqualOrGreaterThan === 'xl',
							'hidden 2xl:table-cell': column.showOnlyWhenScreenIsEqualOrGreaterThan === '2xl',
						}"
						@click="sort(column, $event)"
					>
						<button type="button" class="focus:outline-none">{{ column.name }}</button>
						<i v-if="column.isSort && column.sortOrientation == 'ASC'" class="fa-solid fa-sort-up fa-fw text-indigo-600"></i> 
						<i v-if="column.isSort && column.sortOrientation == 'DESC'" class="fa-solid fa-sort-down fa-fw text-indigo-600"></i>
						<button type="button" v-if="column.isSort" class="text-[10px] text-indigo-600 focus:outline-none">{{ column.sortPriority }}</button>
					</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-gray-200 bg-transparent">
				<tr v-if="tableData.length === 0">
					<td :colspan="tableColumn.length" class="py-4 pl-4 pr-4 text-sm text-gray-500 dark:text-gray-400 sm:pl-0">
						No data found.
					</td>
				</tr>
				<tr v-for="data in tableData">
					<td v-for="column in tableColumn" class="py-4 pl-4 pr-4 text-sm text-gray-500 sm:pl-0"
						:class="{
							'font-medium text-gray-900 dark:text-gray-400': column.isBold,
							'hidden sm:table-cell': column.showOnlyWhenScreenIsEqualOrGreaterThan === 'sm',
							'hidden md:table-cell': column.showOnlyWhenScreenIsEqualOrGreaterThan === 'md',
							'hidden lg:table-cell': column.showOnlyWhenScreenIsEqualOrGreaterThan === 'lg',
							'hidden xl:table-cell': column.showOnlyWhenScreenIsEqualOrGreaterThan === 'xl',
							'hidden 2xl:table-cell': column.showOnlyWhenScreenIsEqualOrGreaterThan === '2xl',
						}"
					>
						{{ data[column.attributeName] }}
						<dl v-if="column.isStackedColumn" class="font-normal">
							<template v-for="stackedColumn in tableColumn">
								<dd class="mt-1 truncate text-gray-500"
									v-if="stackedColumn.attributeName !== column.attributeName"
									:class="{
										'sm:hidden': stackedColumn.showOnlyWhenScreenIsEqualOrGreaterThan === 'sm',
										'md:hidden': stackedColumn.showOnlyWhenScreenIsEqualOrGreaterThan === 'md',
										'lg:hidden': stackedColumn.showOnlyWhenScreenIsEqualOrGreaterThan === 'lg',
										'xl:hidden': stackedColumn.showOnlyWhenScreenIsEqualOrGreaterThan === 'xl',
										'2xl:hidden': stackedColumn.showOnlyWhenScreenIsEqualOrGreaterThan === '2xl',
									}"
								>{{ stackedColumn.name }}: {{ data[stackedColumn.attributeName] }}</dd>
							</template>
						</dl>
					</td>
				</tr>
			</tbody>
		</table>

		<div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-600 bg-transparent py-4 px-4 sm:px-0">
			<div class="flex flex-1 justify-between sm:hidden">
				<a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
				<a href="#" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
			</div>
			<div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
				<div>
					<p class="text-sm text-gray-700 dark:text-gray-400">
						Showing
						<span class="font-medium">{{ tablePagination.from }}</span>
						to
						<span class="font-medium">{{ tablePagination.to }}</span>
						of
						<span class="font-medium">{{ tablePagination.total }}</span>
						results
					</p>
				</div>
				<div>
					<nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
						<!-- First Page Button -->
						<button type="button" @click="$emit('update:tablePage', 1)" class="relative flex w-12 items-center justify-center cursor-pointer rounded-l-md text-sm text-gray-400 dark:text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-500 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
							:class="{
								'pointer-events-none cursor-not-allowed': tablePagination.prevPageUrl === null || tablePagination.prevPageUrl === undefined,
							}"
						>
							<i class="fa-solid fa-angles-left fa-fw"></i>
							<i v-if="tablePagination.prevPageUrl === null || tablePagination.prevPageUrl === undefined" class="fa-regular fa-ban absolute text-2xl text-gray-400 dark:text-gray-500 fa-fw"></i>
						</button>

						<!-- Previous Page Button -->
						<button type="button" @click="$emit('update:tablePage', tablePagination.currentPage - 1)" class="relative flex w-12 items-center justify-center text-gray-400 dark:text-gray-400 text-sm font-semibold ring-1 ring-inset ring-gray-300 dark:ring-gray-500 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
							:class="{
								'pointer-events-none cursor-not-allowed': tablePagination.prevPageUrl === null || tablePagination.prevPageUrl === undefined,
							}"
						>
							<i class="fa-solid fa-angle-left fa-fw"></i>
							<i v-if="tablePagination.prevPageUrl === null || tablePagination.prevPageUrl === undefined" class="fa-regular fa-ban absolute text-2xl text-gray-400 dark:text-gray-500 fa-fw"></i>
						</button>
						
						<!-- Pages Button -->
						<button type="button" v-for="link in tablePagination.links" @click="$emit('update:tablePage', link.label)" class="relative inline-flex items-center cursor-pointer px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-500 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
							:class="{
								'pointer-events-none cursor-not-allowed': link.url === null || link.url === undefined,
								'z-10 bg-indigo-600 text-white dark:text-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 hover:bg-indigo-600': link.active,
							}"
						>
							{{ link.label }}
						</button>
						
						<!-- Next Page Button -->
						<button type="button" @click="$emit('update:tablePage', tablePagination.currentPage + 1)" class="relative flex w-12 items-center justify-center cursor-pointer text-sm font-semibold text-gray-400 dark:text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-500 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
							:class="{
								'pointer-events-none cursor-not-allowed': tablePagination.nextPageUrl === null || tablePagination.nextPageUrl === undefined,
							}"
						>
							<i class="fa-solid fa-angle-right fa-fw"></i>
							<i v-if="tablePagination.nextPageUrl === null || tablePagination.nextPageUrl === undefined" class="fa-regular fa-ban absolute text-2xl text-gray-400 dark:text-gray-500 fa-fw"></i>
						</button>

						<!-- Last Page Button -->
						<button type="button" @click="$emit('update:tablePage', tablePagination.lastPage)" class="relative flex w-12 items-center justify-center cursor-pointer text-sm rounded-r-md text-gray-400 dark:text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-500 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
							:class="{
								'pointer-events-none cursor-not-allowed': tablePagination.nextPageUrl === null || tablePagination.nextPageUrl === undefined,
							}"
						>
							<i class="fa-solid fa-angles-right fa-fw"></i>
							<i v-if="tablePagination.nextPageUrl === null || tablePagination.nextPageUrl === undefined" class="fa-regular fa-ban absolute text-2xl text-gray-400 dark:text-gray-500 fa-fw"></i>
						</button>
					</nav>
				</div>
			</div>
		</div>
	</div>
</template>