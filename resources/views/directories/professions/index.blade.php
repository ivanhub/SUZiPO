<x-directory-table
    title="Профессии"
    breadcrumb="Профессии"
    indexRoute="directories.professions.index"
    createRoute="directories.professions.create"
    createLabel="+ Добавить профессию"
    searchPlaceholder="Поиск по профессиям..."
    :items="$professions"
    editRoute="directories.professions.edit"
    destroyRoute="directories.professions.destroy"
    emptyText="Профессии не найдены"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'Наименование профессии', 'field' => 'name', 'width' => '68%', 'bold' => true],
        ['label' => 'Дата создания', 'field' => 'created_at', 'width' => '14%', 'type' => 'date'],
    ]"
/>
