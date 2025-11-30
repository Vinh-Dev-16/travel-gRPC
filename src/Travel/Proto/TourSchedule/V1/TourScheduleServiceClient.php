<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Travel\Proto\TourSchedule\V1;

/**
 */
class TourScheduleServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Travel\Proto\TourSchedule\V1\GetTourScheduleByIdRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetTourScheduleById(\Travel\Proto\TourSchedule\V1\GetTourScheduleByIdRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/tour.v1.TourScheduleService/GetTourScheduleById',
        $argument,
        ['\Travel\Proto\TourSchedule\V1\TourScheduleByIdResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Travel\Proto\TourSchedule\V1\GetListTourScheduleByTourIdRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetListTourScheduleByTourId(\Travel\Proto\TourSchedule\V1\GetListTourScheduleByTourIdRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/tour.v1.TourScheduleService/GetListTourScheduleByTourId',
        $argument,
        ['\Travel\Proto\TourSchedule\V1\ListTourScheduleByTourIdResponse', 'decode'],
        $metadata, $options);
    }

}
