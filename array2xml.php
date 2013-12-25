<?php

  function encode($data) {
    return encodeXml(new \SimpleXMLElement('<array></array>'), $data)->asXML();
  }

  /**
   * Recursively encodes a value as XML.
   *
   * @param \SimpleXMLElement $simplexml
   *   The simplexml element to add the encoding too.
   * @param mixed $value
   *   The value to be encoded.
   * @param string $key
   *   The key in the parent array.
   */
  function encodeXml(\SimpleXMLElement $simplexml, $value, $key = NULL) {
    if (isset($key)) {
      $simplexml = $simplexml->addChild(gettype($value), is_array($value) ? '' : htmlspecialchars($value));
      $simplexml->addAttribute('key', $key);
    }
    if (is_array($value)) {
      foreach ($value as $new_key => $new_value) {
        encodeXml($simplexml, $new_value, $new_key);
      }
    }
    return $simplexml;
  }

  /**
   * {@inheritdoc}
   */
  function decode($raw) {
    return decodeXml(new \SimpleXMLElement($raw));
  }

  /**
   * Decodes the XML produced by encodeXml.
   *
   * @param array $a
   *   This array contains the decoded data.
   * @param \SimpleXMLElement $simplexml
   *   This contains the XML.
   */
  function decodeXml(\SimpleXMLElement $simplexml) {
    $type = $simplexml->getName();
    if ($type == 'array') {
      $value = array();
      foreach ($simplexml as $element) {
        $value[(string) $element['key']] = decodeXml($element);;
      }
    }
    else {
      $value = (string) $simplexml;
      settype($value, $type);
    }
    return $value;
  }

