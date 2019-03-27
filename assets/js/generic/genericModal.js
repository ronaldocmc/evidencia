$('#modal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const title = button.data('title');
    const id = button.data('contentid');

    const modal = $(this);

    const content = $(`#${id}`).html();

    modal.find('.modal-title').text(title);
    modal.find('.content').html(content);
});

$('#modal').on('hide.bs.modal', function (event) {
    control.clearSelectedId();
});