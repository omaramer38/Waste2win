import joblib
import json

# تحميل الموديل والـ vectorizer المدربين
model = joblib.load("model.pkl")
vectorizer = joblib.load("vectorizer.pkl")

# اقرأ السؤال من JSON
with open("question.json", "r", encoding="utf-8") as f:
    data = json.load(f)

question = data["question"]

# توقع الـ label
predicted_label = model.predict(vectorizer.transform([question]))[0]

# احفظ الرد في JSON جديد بالليبل فقط
output = {
    "text": question,
    "response": predicted_label
}

with open("output_answer.json", "w", encoding="utf-8") as f:
    json.dump(output, f, ensure_ascii=False, indent=4)

print("Reply saved in output_answer.json")
