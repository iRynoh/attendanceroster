<template>
    <Page :title="page.title" :actions="page.actions" @action="onPageAction">

        <template #filters v-if="page.toggleFilters">
            <Filters @clear="onFiltersClear">
                <FiltersRow>
                    <FiltersCol>
                        <TextInput name="first_name" :label="trans('users.labels.first_name')" v-model="mainQuery.filters.first_name.value"></TextInput>
                    </FiltersCol>
                    <FiltersCol>
                        <TextInput name="last_name" :label="trans('users.labels.last_name')" v-model="mainQuery.filters.last_name.value"></TextInput>
                    </FiltersCol>
                    <FiltersCol>
                        <TextInput name="email" type="email" :label="trans('users.labels.email')" v-model="mainQuery.filters.email.value"></TextInput>
                    </FiltersCol>
                </FiltersRow>
            </Filters>
        </template>

        <template #default>
            <Table :id="page.id" v-if="table" :headers="table.headers" :sorting="table.sorting" @action="onAction" :actions="table.actions" :records="table.records" :pagination="table.pagination" :is-loading="table.loading" @page-changed="onTablePageChange" @sort="onTableSort">
                <template v-slot:content-id="props">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ props.item.full_name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ trans('users.labels.id') + ': ' + props.item.id }}
                            </div>
                        </div>
                    </div>
                </template>
            </Table>
        </template>
    </Page>
</template>

<script>

import {trans} from "@/helpers/i18n";
import StudentService from "@/services/StudentService";
import {watch, onMounted, defineComponent, reactive, ref} from 'vue';
import {getResponseError, prepareQuery} from "@/helpers/api";
import {useAlertStore} from "@/stores";
import alertHelpers from "@/helpers/alert";
import Page from "@/views/layouts/Page";
import Table from "@/views/components/Table";
import Filters from "@/views/components/filters/Filters";
import FiltersRow from "@/views/components/filters/FiltersRow";
import FiltersCol from "@/views/components/filters/FiltersCol";
import TextInput from "@/views/components/input/TextInput";

export default defineComponent({
    components: {
        TextInput,
        FiltersCol,
        FiltersRow,
        Filters,
        Page,
        Table,
    },
    setup() {
        const service = new StudentService();
        const alertStore = useAlertStore();
        const mainQuery = reactive({
            page: 1,
            search: '',
            sort: '',
            filters: {
                first_name: {
                    value: '',
                    comparison: '='
                },
                last_name: {
                    value: '',
                    comparison: '='
                },
                role: {
                    value: '',
                    comparison: '='
                },
                email: {
                    value: '',
                    comparison: '='
                }
            }
        });

        const page = reactive({
            id: 'list_students',
            title: trans('global.pages.users'),
            actions: [
                {
                    id: 'filters',
                    name: trans('global.buttons.filters'),
                    icon: "fa fa-filter",
                    theme: 'outline',
                },
            ],
            toggleFilters: false,
        });

        const table = reactive({
            headers: {
                id: trans('users.labels.id_pound'),
                first_name: trans('users.labels.first_name'),
                last_name: trans('users.labels.last_name'),
                email: trans('users.labels.email'),
                status: trans('users.labels.status'),
            },
            sorting: {
                first_name: true,
                last_name: true
            },
            pagination: {
                meta: null,
                links: null,
            },
            actions: {
                present: {
                    id: 'present',
                    name: trans('global.actions.present'),
                    icon: "fa fa-thumbs-up",
                    showName: false,
                    type: 'submit'
                },
                absent: {
                    id: 'absent',
                    name: trans('global.actions.absent'),
                    icon: "fa fa-thumbs-down",
                    showName: false,
                    type: 'submit'
                }
            },
            loading: false,
            records: null
        })

        function onTableSort(params) {
            mainQuery.sort = params;
        }

        function onTablePageChange(page) {
            mainQuery.page = page;
        }

        function onPageAction(params) {
            switch (params.action.id) {
                case 'filters':
                    page.toggleFilters = !page.toggleFilters;
                    break;
            }
        }

        function onAction(data) {
            onSubmit(data.item.id, data.action.id);
        }

        async function onSubmit(studentId, status) {
            await service.handleUpdate('edit-student', studentId, { status });
            const studentIndex = table.records.findIndex(student => student.id === studentId);
            if (studentIndex !== -1) {
                // If the student is found in the records, update its status manually
                table.records[studentIndex].status = status;
            }
            return false;
        }

        function onFiltersClear() {
            let clonedFilters = mainQuery.filters;
            for(let key in clonedFilters) {
                clonedFilters[key].value = '';
            }
            mainQuery.filters = clonedFilters;
        }

        function fetchPage(params) {
            table.records = [];
            table.loading = true;
            let query = prepareQuery(params);
            service
                .index(query)
                .then((response) => {
                    table.records = response.data.data;
                    table.pagination.meta = response.data.meta;
                    table.pagination.links = response.data.links;
                    table.loading = false;
                })
                .catch((error) => {
                    alertStore.error(getResponseError(error));
                    table.loading = false;
                });
        }

        watch(mainQuery, (newTableState) => {
            fetchPage(mainQuery);
        });

        onMounted(() => {
            fetchPage(mainQuery);
        });

        return {
            trans,
            page,
            table,
            onTablePageChange,
            onTableSort,
            onPageAction,
            onFiltersClear,
            onAction,
            mainQuery
        }

    },
});
</script>
