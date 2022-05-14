const buttonToggleElement = $(".header-button-toggle");
const sidebarElement = $(".sidebar");
const headerElement = $(".header");
const contentElement = $(".content");

buttonToggleElement.click(function () {
    sidebarElement.toggleClass("sidebar--collapse");
    headerElement.toggleClass("header--fullscreen");
    contentElement.toggleClass("content--fullscreen");
});

function previewImages() {
    var imagesPreview = $("#preview").empty();

    if (this.files) $.each(this.files, readAndPreview);

    function readAndPreview(i, file) {
        if (!/\.(jpe?g|png|gif)$/i.test(file.name)) {
            return alert(file.name + " is not an image");
        }

        var reader = new FileReader();

        $(reader).on("load", function () {
            imagesPreview.append(
                $("<img/>", {
                    src: this.result,
                    width: 300,
                    objectFit: "cover",
                })
            );
        });

        reader.readAsDataURL(file);
    }
}

$("#fileUploader").on("change", previewImages);
