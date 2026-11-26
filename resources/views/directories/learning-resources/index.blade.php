<x-directory-table
    title="Образовательные ресурсы"
    breadcrumb="Образовательные ресурсы"
    indexRoute="directories.learning-resources.index"
    createRoute="directories.learning-resources.create"
    createLabel="+ Добавить ресурс"
    searchPlaceholder="Поиск..."
    :items="$learningResources"
    editRoute="directories.learning-resources.edit"
    destroyRoute="directories.learning-resources.destroy"
    emptyText="Записей не найдено"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'Наименование', 'field' => 'name', 'width' => '68%', 'bold' => true],
        ['label' => 'Дата создания', 'field' => 'created_at', 'width' => '14%', 'type' => 'date'],
    ]"
/>
