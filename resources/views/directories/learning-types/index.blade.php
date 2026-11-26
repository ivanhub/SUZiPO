<x-directory-table
    title="Виды обучения"
    breadcrumb="Виды обучения"
    indexRoute="directories.learning-types.index"
    createRoute="directories.learning-types.create"
    createLabel="+ Добавить вид"
    searchPlaceholder="Поиск..."
    :items="$learningTypes"
    editRoute="directories.learning-types.edit"
    destroyRoute="directories.learning-types.destroy"
    emptyText="Записей не найдено"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'Наименование', 'field' => 'name', 'width' => '68%', 'bold' => true],
        ['label' => 'Дата создания', 'field' => 'created_at', 'width' => '14%', 'type' => 'date'],
    ]"
/>
