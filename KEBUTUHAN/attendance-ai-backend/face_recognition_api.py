import cv2
import numpy as np
from flask import Flask, request, jsonify
import base64

app = Flask(__name__)

# Load pre-trained OpenCV face detector (Haar Cascade)
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + "haarcascade_frontalface_default.xml")

@app.route('/scan-face', methods=['POST'])
def scan_face():
    """Menerima gambar base64 dan mendeteksi wajah."""
    data = request.json
    image_data = data.get("image")
    
    if not image_data:
        return jsonify({"success": False, "message": "Gambar tidak ditemukan!"}), 400
    
    # Decode gambar dari base64
    nparr = np.frombuffer(base64.b64decode(image_data.split(',')[1]), np.uint8)
    img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
    gray_img = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    
    # Deteksi wajah
    faces = face_cascade.detectMultiScale(gray_img, scaleFactor=1.1, minNeighbors=5, minSize=(30, 30))
    
    if len(faces) > 0:
        return jsonify({"success": True, "faces_detected": len(faces), "message": "Wajah terdeteksi!"})
    
    return jsonify({"success": False, "message": "Tidak ada wajah terdeteksi!"})

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)