// cloudinary.js

const CLOUD_NAME = 'dxvobpwzc';
const API_KEY = '411783751415228';
const API_SECRET = 'PkoJscrfmOarSoZvnBag29GZzPk';

// Utility function to generate the API signature
function generateSignature(params, apiSecret) {
    const sortedKeys = Object.keys(params).sort();
    const signatureString = sortedKeys
        .map(key => `${key}=${params[key]}`)
        .join('&') + `&api_secret=${apiSecret}`;
    return CryptoJS.SHA1(signatureString).toString(CryptoJS.enc.Hex);
}

// Function to delete an image from Cloudinary
function deleteImage(publicId) {
    const timestamp = Math.floor(Date.now() / 1000);
    const params = {
        public_id: publicId,
        api_key: API_KEY,
        timestamp: timestamp
    };
    const signature = generateSignature(params, API_SECRET);
    params.signature = signature;

    fetch(`https://api.cloudinary.com/v1_1/${CLOUD_NAME}/image/destroy`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(params).toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.result === 'ok') {
            console.log('Image deleted successfully:', data);
        } else {
            console.error('Failed to delete image:', data);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}