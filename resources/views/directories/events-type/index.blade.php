<x-directory-table
    title="Типы мероприятий"
    breadcrumb="Типы мероприятий"
    indexRoute="directories.events-type.index"
    createRoute="directories.events-type.create"
    createLabel="+ Добавить тип"
    searchPlaceholder="Поиск..."
    :items="$eventsTypes"
    editRoute="directories.events-type.edit"
    destroyRoute="directories.events-type.destroy"
    emptyText="Записей не найдено"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'Наименование', 'field' => 'name', 'width' => '68%', 'bold' => true],
        ['label' => 'Дата создания', 'field' => 'created_at', 'width' => '14%', 'type' => 'date'],
    ]"
/>
