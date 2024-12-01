const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

// Configura la conexión a la base de datos
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'registro_db' // Cambia por el nombre de tu base de datos
});

// Endpoint para obtener los datos del perfil
app.get('/perfil/:id', (req, res) => {
    const userId = req.params.id;
    const query = 'SELECT id, correo, usuario, lugarResidencia FROM usuarios WHERE id = ?';
    db.query(query, [userId], (err, result) => {
        if (err) {
            console.error(err);
            res.status(500).send('Error al obtener los datos del usuario');
        } else if (result.length === 0) {
            res.status(404).send('Usuario no encontrado');
        } else {
            res.json(result[0]); // Devuelve solo el primer resultado
        }
    });
});

// Inicia el servidor
app.listen(3000, () => {
    console.log('Servidor ejecutándose en http://localhost:3000');
});
