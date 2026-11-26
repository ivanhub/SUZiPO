<x-directory-table
    title="Учебные центры (Провайдеры)"
    breadcrumb="Провайдеры"
    indexRoute="directories.providers.index"
    createRoute="directories.providers.create"
    createLabel="+ Добавить провайдера"
    searchPlaceholder="Поиск по провайдерам..."
    :items="$providers"
    editRoute="directories.providers.edit"
    destroyRoute="directories.providers.destroy"
    emptyText="Провайдеры не найдены"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'Наименование', 'field' => 'name', 'width' => '68%', 'bold' => true],
        ['label' => 'Дата создания', 'field' => 'created_at', 'width' => '14%', 'type' => 'date'],
    ]"
/>
