@props(['id','title','body'])

<li
  x-data="notes({
    id: {{ $id }},
    title: '{{ esc_js($title) }}',
    body:  '{{ esc_js($body)  }}'
  })"
  class="note-item link-list__item"
>
  <input
    x-ref="title"
    type="text"
    class="note-title-field"
    x-model="title"
    :readonly="! editing"
    :class="{ 'note-active-field': editing }"
  >

  <span class="edit-note" @click="toggleEdit()">
    <i :class="editing ? 'fa fa-times' : 'fa fa-pencil'"></i>
    <span x-text="editing ? 'Cancel' : 'Edit'"></span>
  </span>

  <span class="delete-note" @click="deleteNote()">
    <i class="fa fa-trash-o"></i> Delete
  </span>

  <textarea
    class="note-body-field"
    x-model="body"
    :readonly="! editing"
    :class="{ 'note-active-field': editing }"
  ></textarea>

  <span
    class="btn btn--blue btn--small"
    x-show="editing"
    @click="updateNote()"
  >
    <i class="fa fa-arrow-right"></i> Save
  </span>
</li>
