<x-directory-table
    title="Кураторы"
    breadcrumb="Кураторы"
    indexRoute="directories.curators.index"
    createRoute="directories.curators.create"
    createLabel="+ Добавить куратора"
    searchPlaceholder="Поиск по ФИО..."
    :items="$curators"
    editRoute="directories.curators.edit"
    destroyRoute="directories.curators.destroy"
    emptyText="Кураторы не найдены"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'ФИО куратора', 'field' => 'fio', 'width' => '68%', 'bold' => true],
        ['label' => 'Дата создания', 'field' => 'created_at', 'width' => '14%', 'type' => 'date'],
    ]"
/>
