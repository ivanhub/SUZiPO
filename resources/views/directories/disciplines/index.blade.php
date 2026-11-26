<x-directory-table
    title="Дисциплины"
    breadcrumb="Дисциплины"
    indexRoute="directories.disciplines.index"
    createRoute="directories.disciplines.create"
    createLabel="+ Добавить дисциплину"
    searchPlaceholder="Поиск по дисциплинам..."
    :items="$disciplines"
    editRoute="directories.disciplines.edit"
    destroyRoute="directories.disciplines.destroy"
    emptyText="Дисциплины не найдены"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'Наименование дисциплины', 'field' => 'name', 'width' => '68%', 'bold' => true],
        ['label' => 'Дата создания', 'field' => 'created_at', 'width' => '14%', 'type' => 'date'],
    ]"
/>
