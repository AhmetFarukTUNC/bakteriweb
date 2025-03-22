from flask import Flask, request, jsonify
import tensorflow as tf
import numpy as np
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing.image import img_to_array
from rembg import remove
from PIL import Image
from io import BytesIO
import os
import time

# Flask uygulamasını başlat
app = Flask(__name__)

# Modeli yükle
MODEL_PATH = "tl_unet_98_7label.keras"
model = load_model(MODEL_PATH)

# Resim özellikleri
IMAGE_SIZE = (256, 256)
CLASS_NAMES = ['Acineto', 'Ecoli', 'Enterobakter', 'Kpneumoniae', 'Proteus', 'PsAeruginosa', 'StaphAureus']

def predict_image(image_bytes):
    img = Image.open(image_bytes).convert('RGB').resize(IMAGE_SIZE)
    img_array = img_to_array(img) / 255.0
    img_array = img_array[np.newaxis, ...]

    pred_probs = model.predict(img_array)[0]
    max_index = np.argmax(pred_probs)
    predicted_class = CLASS_NAMES[max_index]
    max_probability = float(pred_probs[max_index])

    return predicted_class, max_probability

@app.route("/predict", methods=["POST"])
def predict():
    start_time = time.time()

    if "file" not in request.files:
        return jsonify({"error": "Lütfen bir dosya yükleyin!"}), 400

    file = request.files["file"]

    try:
        # Resmi belleğe yükle
        load_start = time.time()
        image = Image.open(file.stream)
        load_end = time.time()

        # Arka plan kaldırma
        remove_start = time.time()
        output = remove(image)
        remove_end = time.time()

        # Tahmin işlemi
        predict_start = time.time()
        temp_buffer = BytesIO()
        output.save(temp_buffer, format="PNG")
        temp_buffer.seek(0)
        predicted_class, max_probability = predict_image(temp_buffer)
        predict_end = time.time()

        # İşlem süreleri
        total_time = time.time() - start_time
        load_time = load_end - load_start
        remove_time = remove_end - remove_start
        predict_time = predict_end - predict_start

        return jsonify({
            "predicted_class": predicted_class,
            "max_probability": max_probability,
            "total_time": total_time,
            "load_time": load_time,
            "remove_time": remove_time,
            "predict_time": predict_time
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500

# Sunucuyu başlat
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=False, threaded=True)
