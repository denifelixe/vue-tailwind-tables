<script setup>
	import { computed } from 'vue';

	const props = defineProps([
		'tableColumn',
		'tableData',
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
			},
			{
				name: 'Registered At',
				attributeName: 'registeredAt',
				showWhenScreenIsEqualOrGreaterThan: 'sm',
				isStacked: true,
			},
			{
				name: 'Unregistered At',
				attributeName: 'unregisteredAt',
				showWhenScreenIsEqualOrGreaterThan: 'sm',
				isStacked: true,
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
				unregisteredAt: '2021-08-01 00:00:00',
			},
			{
				email: 'denibbandroid@gmail.com',
				registeredAt: '2021-08-01 00:00:00',
				unregisteredAt: '2021-08-01 00:00:00',
			},
		];
	});
</script>

<template>
	<!-- This component is from TailwindUI -->
    <!-- https://tailwindui.com/components/application-ui/lists/tables#component-e56f750c63d4e53a24f5f0bf9fd7b52a -->
	<div class="-mx-4 sm:-mx-0">
		<table class="min-w-full divide-y divide-gray-300">
			<thead>
				<tr>
					<th v-for="column in tableColumn" scope="col" class="py-4 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-0"
						:class="{
							'hidden sm:table-cell': column.showWhenScreenIsEqualOrGreaterThan === 'sm',
							'hidden md:table-cell': column.showWhenScreenIsEqualOrGreaterThan === 'md',
							'hidden lg:table-cell': column.showWhenScreenIsEqualOrGreaterThan === 'lg',
							'hidden xl:table-cell': column.showWhenScreenIsEqualOrGreaterThan === 'xl',
							'hidden 2xl:table-cell': column.showWhenScreenIsEqualOrGreaterThan === '2xl',
						}"
					>{{ column.name }}</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-gray-200 bg-white">
				<tr v-for="data in tableData">
					<td v-for="column in tableColumn" class="py-4 pl-4 pr-4 text-sm text-gray-500 sm:pl-0"
						:class="{
							'font-medium text-gray-900': column.isBold,
							'hidden sm:table-cell': column.showWhenScreenIsEqualOrGreaterThan === 'sm',
							'hidden md:table-cell': column.showWhenScreenIsEqualOrGreaterThan === 'md',
							'hidden lg:table-cell': column.showWhenScreenIsEqualOrGreaterThan === 'lg',
							'hidden xl:table-cell': column.showWhenScreenIsEqualOrGreaterThan === 'xl',
							'hidden 2xl:table-cell': column.showWhenScreenIsEqualOrGreaterThan === '2xl',
						}"
					>
						{{ data[column.attributeName] }}
						<dl v-if="column.isStackedColumn" class="font-normal">
							<template v-for="stackedColumn in tableColumn">
								<dd class="mt-1 truncate text-gray-500"
									v-if="stackedColumn.attributeName !== column.attributeName"
									:class="{
										'sm:hidden': stackedColumn.showWhenScreenIsEqualOrGreaterThan === 'sm',
										'md:hidden': stackedColumn.showWhenScreenIsEqualOrGreaterThan === 'md',
										'lg:hidden': stackedColumn.showWhenScreenIsEqualOrGreaterThan === 'lg',
										'xl:hidden': stackedColumn.showWhenScreenIsEqualOrGreaterThan === 'xl',
										'2xl:hidden': stackedColumn.showWhenScreenIsEqualOrGreaterThan === '2xl',
									}"
								>{{ stackedColumn.name }}: {{ data[stackedColumn.attributeName] }}</dd>
							</template>
						</dl>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>