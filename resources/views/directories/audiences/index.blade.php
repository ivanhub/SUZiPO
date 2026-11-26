<x-directory-table
    title="Аудитории"
    breadcrumb="Аудитории"
    indexRoute="directories.audiences.index"
    createRoute="directories.audiences.create"
    createLabel="+ Добавить аудиторию"
    searchPlaceholder="Поиск по аудиториям..."
    :items="$audiences"
    editRoute="directories.audiences.edit"
    destroyRoute="directories.audiences.destroy"
    emptyText="Аудитории не найдены"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'Номер / Название', 'field' => 'number', 'width' => '24%', 'bold' => true],
        ['label' => 'Местоположение', 'field' => 'location', 'width' => '44%'],
        ['label' => 'Мест', 'field' => 'seats', 'width' => '14%'],
    ]"
/>
