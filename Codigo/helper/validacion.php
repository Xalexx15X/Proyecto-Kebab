<?php

    /*
        Clase para validar datos
        
        Métodos:
            Requerido($campo): Verifica si un campo es requerido
            EnteroRango($campo, $min, $max): Verifica si un campo es un número entero entre un rango opcional
            Telefono($campo): Verifica si un campo es un teléfono válido (formato genérico)
            Gmail($campo): Verifica si un campo es un correo Gmail válido
            Numero($campo): Verifica si un campo es un número
            Cadena($campo): Verifica si un campo es una cadena (string)
            Json($campo): Verifica si un campo es un JSON válido
            FechaHora($campo): Verifica si un campo es una fecha y hora válida (formato: YYYY-MM-DD HH:MM:SS)
            getValor($campo): Devuelve el valor de un campo del POST
            getSelected($campo, $valor): Devuelve el valor de un campo seleccionado
            getChecked($campo, $valor): Devuelve el valor de un campo seleccionado
            
        TODO: Implementar métodos para validar datos (Requerido, EnteroRango, Telefono, Gmail, Numero, Cadena, Json, FechaHora, getValor, getSelected, getChecked)
        * Requerido: Verifica si un campo es requerido
        * EnteroRango: Verifica si un campo es un número entero entre un rango opcional
        * Telefono: Verifica si un campo es un teléfono válido (formato genérico)
        * Gmail: Verifica si un campo es un correo Gmail válido
        * Numero: Verifica si un campo es un número
        * Cadena: Verifica si un campo es una cadena (string)
        * Json: Verifica si un campo es un JSON válido
        * FechaHora: Verifica si un campo es una fecha y hora válida (formato: YYYY-MM-DD HH:MM:SS)
        * getValor: Devuelve el valor de un campo del POST
        * getSelected: Devuelve el valor de un campo seleccionado    
        * getChecked: Devuelve el valor de un campo seleccionado    
    */

class Validacion
{
    // Array de errores
    private $errores;

    // Constructor
    public function __construct()
    {
        $this->errores = array();
    }

    /**
     * Comprueba si está vacío
     *
     * @param string $campo
     * @return boolean
     */
    public function Requerido($campo)
    {
        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
            $this->errores[$campo] = "El campo $campo no puede estar vacío";
            return false;
        }
        return true;
    }

    /**
     * Valida si el campo es un número entero entre un rango opcional
     *
     * @param string $campo
     * @param int $min
     * @param int $max
     * @return boolean
     */
    public function EnteroRango($campo, $min = PHP_INT_MIN, $max = PHP_INT_MAX)
    {
        if (!filter_var($_POST[$campo], FILTER_VALIDATE_INT, ["options" => ["min_range" => $min, "max_range" => $max]])) {
            $this->errores[$campo] = "Debe ser entero entre $min y $max";
            return false;
        }
        return true;
    }

    /**
     * Valida si es un teléfono válido (formato genérico)
     *
     * @param string $campo
     * @return boolean
     */
    public function Telefono($campo)
    {
        if (!preg_match("/^\+?[0-9]{7,15}$/", $_POST[$campo])) {
            $this->errores[$campo] = "El campo $campo debe ser un teléfono válido";
            return false;
        }
        return true;
    }

    /**
     * Valida si es un correo Gmail válido
     *
     * @param string $campo
     * @return boolean
     */
    public function Gmail($campo)
    {
        if (!filter_var($_POST[$campo], FILTER_VALIDATE_EMAIL) || !str_ends_with($_POST[$campo], "@gmail.com")) {
            $this->errores[$campo] = "Debe ser un correo de Gmail válido";
            return false;
        }
        return true;
    }

    /**
     * Valida si el campo es un número
     *
     * @param string $campo
     * @return boolean
     */
    public function Numero($campo)
    {
        if (!is_numeric($_POST[$campo])) {
            $this->errores[$campo] = "El campo $campo debe ser un número";
            return false;
        }
        return true;
    }

    /**
     * Valida si el campo es una cadena (string)
     *
     * @param string $campo
     * @return boolean
     */
    public function Cadena($campo)
    {
        if (!is_string($_POST[$campo]) || empty(trim($_POST[$campo]))) {
            $this->errores[$campo] = "El campo $campo debe ser una cadena válida";
            return false;
        }
        return true;
    }

    /**
     * Valida si el campo es un JSON válido
     *
     * @param string $campo
     * @return boolean
     */
    public function Json($campo)
    {
        json_decode($_POST[$campo]);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->errores[$campo] = "El campo $campo debe ser un JSON válido";
            return false;
        }
        return true;
    }

    /**
     * Valida si el campo es una fecha y hora válida (formato: YYYY-MM-DD HH:MM:SS)
     *
     * @param string $campo
     * @return boolean
     */
    public function FechaHora($campo)
    {
        $formato = 'Y-m-d H:i:s';
        $fecha = DateTime::createFromFormat($formato, $_POST[$campo]);
        if (!$fecha || $fecha->format($formato) !== $_POST[$campo]) {
            $this->errores[$campo] = "El campo $campo debe tener el formato YYYY-MM-DD HH:MM:SS";
            return false;
        }
        return true;
    }

    /**
     * Comprueba si hay errores
     *
     * @return boolean
     */
    public function ValidacionPasada()
    {
        return count($this->errores) === 0;
    }

    /**
     * Imprime un error de un campo
     *
     * @param string $campo
     * @return string
     */
    public function ImprimirError($campo)
    {
        return isset($this->errores[$campo]) ? '<span class="error_mensaje">' . $this->errores[$campo] . '</span>' : '';
    }

    /**
     * Devuelve el valor de un campo del POST
     *
     * @param string $campo
     * @return mixed
     */
    public function getValor($campo)
    {
        return isset($_POST[$campo]) ? $_POST[$campo] : '';
    }

    /**
     * Devuelve 'selected' para listas desplegables
     *
     * @param string $campo
     * @param mixed $valor
     * @return string
     */
    public function getSelected($campo, $valor)
    {
        return isset($_POST[$campo]) && $_POST[$campo] == $valor ? 'selected' : '';
    }

    /**
     * Devuelve 'checked' para checkboxes o radios
     *
     * @param string $campo
     * @param mixed $valor
     * @return string
     */
    public function getChecked($campo, $valor)
    {
        return isset($_POST[$campo]) && $_POST[$campo] == $valor ? 'checked' : '';
    }
}
