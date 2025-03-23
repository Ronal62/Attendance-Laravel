import os
import cv2
import numpy as np
from PIL import Image

# Path ke dataset & model
dataset_path = "dataset"
trainer_path = "models/trainer.yml"

# Cek jika folder dataset tidak ada, buat otomatis
if not os.path.exists(dataset_path):
    print(f"⚠️ Folder '{dataset_path}' tidak ditemukan. Membuat folder...")
    os.makedirs(dataset_path)

if not os.path.exists("models"):
    os.makedirs("models")

# Face recognizer
face_recognizer = cv2.face.LBPHFaceRecognizer_create()
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + "haarcascade_frontalface_default.xml")

def get_images_and_labels():
    face_samples = []
    ids = []
    
    image_paths = [os.path.join(dataset_path, f) for f in os.listdir(dataset_path) if f.endswith(".jpg")]
    
    for image_path in image_paths:
        image = Image.open(image_path).convert("L")  # Convert ke grayscale
        image_np = np.array(image, "uint8")
        
        filename = os.path.split(image_path)[-1]  # Ambil nama file
        try:
            user_id = int(filename.split("_")[1])  # Ambil angka setelah "user_"
        except ValueError:
            print(f"⚠️ Format nama file salah: {filename}. Gunakan format 'user_1_1.jpg'")
            continue  # Lewati file jika format salah
        
        faces = face_cascade.detectMultiScale(image_np)
        
        for (x, y, w, h) in faces:
            face_samples.append(image_np[y:y+h, x:x+w])
            ids.append(user_id)
    
    return face_samples, ids

print("🔄 Training wajah...")

faces, ids = get_images_and_labels()
if len(faces) == 0:
    print("⚠️ Tidak ada data wajah dalam folder 'dataset'. Tambahkan gambar wajah terlebih dahulu!")
else:
    face_recognizer.train(faces, np.array(ids))
    face_recognizer.save(trainer_path)
    print(f"✅ Model wajah berhasil disimpan di {trainer_path}")
