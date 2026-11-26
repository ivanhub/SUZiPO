<x-directory-table
    title="Города"
    breadcrumb="Города"
    indexRoute="directories.cities.index"
    createRoute="directories.cities.create"
    createLabel="+ Добавить город"
    searchPlaceholder="Поиск по городам..."
    :items="$cities"
    editRoute="directories.cities.edit"
    destroyRoute="directories.cities.destroy"
    emptyText="Города не найдены"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'Город', 'field' => 'city', 'width' => '68%', 'bold' => true],
        ['label' => 'Дата создания', 'field' => 'created_at', 'width' => '14%', 'type' => 'date'],
    ]"
/>
