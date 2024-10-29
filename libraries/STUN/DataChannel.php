<?php
namespace STUN;

readonly class DataChannel{
  public int $channel;

  public int $length;

  public string $data;

  public function __construct(string $data){
    [
      'channel'=>$channel, 
      'length'=>$length, 
      'data'=>$data
    ] = unpack("nchannel/nlength/a*data", $data);

    $this->channel = $channel;
    $this->length = $length;
    $this->data = $data;
  }
}