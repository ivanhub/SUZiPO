<x-directory-table
    title="Причины обучения"
    breadcrumb="Причины обучения"
    indexRoute="directories.learn-reasons.index"
    createRoute="directories.learn-reasons.create"
    createLabel="+ Добавить причину"
    searchPlaceholder="Поиск..."
    :items="$learnReasons"
    editRoute="directories.learn-reasons.edit"
    destroyRoute="directories.learn-reasons.destroy"
    emptyText="Записей не найдено"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'Наименование', 'field' => 'name', 'width' => '68%', 'bold' => true],
        ['label' => 'Дата создания', 'field' => 'created_at', 'width' => '14%', 'type' => 'date'],
    ]"
/>
