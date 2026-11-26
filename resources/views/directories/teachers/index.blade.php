<x-directory-table
    title="Преподаватели"
    breadcrumb="Преподаватели"
    indexRoute="directories.teachers.index"
    createRoute="directories.teachers.create"
    createLabel="+ Добавить преподавателя"
    searchPlaceholder="Поиск по ФИО..."
    :items="$teachers"
    editRoute="directories.teachers.edit"
    destroyRoute="directories.teachers.destroy"
    emptyText="Преподаватели не найдены"
    :columns="[
        ['label' => 'ID', 'field' => 'id', 'width' => '8%', 'bold' => true],
        ['label' => 'ФИО преподавателя', 'field' => 'fio', 'width' => '68%', 'bold' => true],
        ['label' => 'Дата создания', 'field' => 'created_at', 'width' => '14%', 'type' => 'date'],
    ]"
/>
