$('#modal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const title = button.data('title');
    const id = button.data('contentid');
    const modal = $(this);
    const content = $(`#${id}`).html();

    myControl.setSelectedId($(button[0]).val());

    console.log(button[0]);


    modal.find('.modal-title').text(title);
    modal.find('.content').html(content);
});

$('#modal').on('hide.bs.modal', function (event) {
    myControl.clearSelectedId();
});