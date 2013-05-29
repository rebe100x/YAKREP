<?php
/******************************************************************************
 *
 *                                 Cloudview
 *
 *                            PHP Push API Client Library.
 *
 *                       Copyright (c) 2009 Exalead S.A.
 *
 *****************************************************************************/

/**
 * Binary part of a document.
 * 
 * The Part represents a part of the content to be converted and indexed. In the
 * most common case, only one part is needed, but you may need to link some
 * attachments to the content.
 * <p />
 * A part is composed of the following information:
 * <ul>
 * <li>name: the part name that might be used for configuring the handling of a
 * given part in the indexing chain</li>
 * <li>content: represented either through an array of bytes or a stream, or
 * an instance of a ContentStream derivated class</li>
 * <li>directives: a set of directives to control the processing of the part in
 * the indexing chain (for instance, content mime type, filename, encoding, ...)
 * </li>
 * </ul>
 */
class Part {
  /**
   * Constructor specifying the content as either raw data, or a resource file.
   * Arguments are given in a key => value array.
   * 
   * @param content (mandatory)
   *          Document content.
   * @param directives
   *          Part metas.
   *          The list of optional supported directives:
   *          -  filename: the filename of the document.
   *          -  mimeHint: the hint mime parameter.
   *          -  mime: the forced mime (use very carefully).
   *          -  encoding: the encoding of the document.
   *
   * Example:
   *   $part = new Part(array('content' =>
   *                                     '<html><body>Hello, world<body><html>',
   *                          'directives' =>
   *                            array('mimeHint' => 'text/html',
   *                                  'encoding' => 'utf-8')
   *                         )
   *                   );
   */
  function __construct($args) {
    if (!is_array($args)) {
      $this->badTypePapiThrow($args, 'array');
    }
    // Can be empty for updates
    if (isset($args['content'])) {
      $this->content = $args['content'];
    }
    // "this->metas" are directives
    $this->metas = array();
    if (isset($args['directives'])) {
      if (is_array($args['directives'])) {
        $this->metas = $args['directives'];
      } else if ($args['directives'] !== false) {
        $this->badTypePapiThrow($args['directives'], 'array');
      }
    }
  }
  
  /* Content (value or stream) */
  public $content;

  /** Part meta data. Must be an array() of key => value pairs or an
      array() of key => stream pairs.. */
  public $metas;
}

?>