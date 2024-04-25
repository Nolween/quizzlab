export function convertImgSrcToFile(imgSrc) {
    return fetch(imgSrc)
        .then(response => response.blob())
        .then(blob => {
            return new File([blob], 'filename.jpeg', {type: blob.type});
        })
        .catch(error => {
            console.error('Error:', error);
            throw error;
        });
}
