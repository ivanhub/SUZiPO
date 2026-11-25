<x-directory-table
    title="Курсы"
    breadcrumb="Курсы"
    indexRoute="directories.courses.index"
    createRoute="directories.courses.create"
    createLabel="+ Добавить курс"
    searchPlaceholder="Поиск по курсам..."
    :items="$courses"
    editRoute="directories.courses.edit"
    destroyRoute="directories.courses.destroy"
    emptyText="Нет курсов"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'Option Value', 'field' => 'option_value', 'width' => '16%'],
        ['label' => 'Курс', 'field' => 'course', 'width' => '52%', 'bold' => true],
        ['label' => 'Дата создания', 'field' => 'created_at', 'width' => '14%', 'type' => 'date'],
    ]"
/>
