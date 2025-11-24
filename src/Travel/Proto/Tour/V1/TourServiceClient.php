<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Travel\Proto\Tour\V1;

/**
 * Service definition
 */
class TourServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Travel\Proto\Tour\V1\GetTourByIdRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetTourById(\Travel\Proto\Tour\V1\GetTourByIdRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/tour.v1.TourService/GetTourById',
        $argument,
        ['\Travel\Proto\Tour\V1\TourResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Travel\Proto\Tour\V1\ListToursRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function ListTours(\Travel\Proto\Tour\V1\ListToursRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/tour.v1.TourService/ListTours',
        $argument,
        ['\Travel\Proto\Tour\V1\ListToursResponse', 'decode'],
        $metadata, $options);
    }

}
