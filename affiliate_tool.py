import uuid
import json
from flask import Flask, request, jsonify, redirect

app = Flask(__name__)

# Data structure to store affiliate information
affiliates = {
    # Example structure
    # "affiliate_id": {
    #     "name": "John Doe",
    #     "email": "john@example.com",
    #     "clicks": 0,
    #     "conversions": 0,
    #     "affiliate_link": "http://example.com/affiliate/<affiliate_id>"
    # }
}

# Route to create a new affiliate
@app.route("/create_affiliate", methods=["POST"])
def create_affiliate():
    data = request.json
    name = data.get("name")
    email = data.get("email")

    if not name or not email:
        return jsonify({"error": "Name and email are required."}), 400

    affiliate_id = str(uuid.uuid4())
    affiliate_link = f"http://localhost:5000/affiliate/{affiliate_id}"

    affiliates[affiliate_id] = {
        "name": name,
        "email": email,
        "clicks": 0,
        "conversions": 0,
        "affiliate_link": affiliate_link
    }

    return jsonify({"affiliate_id": affiliate_id, "affiliate_link": affiliate_link})

# Route to simulate a user clicking an affiliate link
@app.route("/affiliate/<affiliate_id>", methods=["GET"])
def affiliate_click(affiliate_id):
    affiliate = affiliates.get(affiliate_id)
    if not affiliate:
        return jsonify({"error": "Affiliate not found."}), 404

    # Increment click count
    affiliate["clicks"] += 1

    # Simulate redirecting to a product page
    return redirect("http://example.com/product")

# Route to register a conversion (e.g., a sale)
@app.route("/register_conversion", methods=["POST"])
def register_conversion():
    data = request.json
    affiliate_id = data.get("affiliate_id")

    affiliate = affiliates.get(affiliate_id)
    if not affiliate:
        return jsonify({"error": "Affiliate not found."}), 404

    # Increment conversion count
    affiliate["conversions"] += 1

    return jsonify({"message": "Conversion registered successfully."})

# Route to get affiliate statistics
@app.route("/affiliate_stats/<affiliate_id>", methods=["GET"])
def affiliate_stats(affiliate_id):
    affiliate = affiliates.get(affiliate_id)
    if not affiliate:
        return jsonify({"error": "Affiliate not found."}), 404

    return jsonify({
        "name": affiliate["name"],
        "email": affiliate["email"],
        "clicks": affiliate["clicks"],
        "conversions": affiliate["conversions"],
        "affiliate_link": affiliate["affiliate_link"]
    })

if __name__ == "__main__":
    app.run(debug=True)
