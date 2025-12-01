<?php

namespace Travel\Proto\TourSchedule\V1;



/**
 * Service definition for TourScheduleService
 */
interface TourScheduleServiceGrpcInterface extends ServiceInterface
{
    // Service name from proto file: package.service
    public const NAME = 'tour.v1.TourScheduleService';

    /**
     * Get a tour schedule by ID
     *
     * @param ContextInterface $ctx
     * @param GetTourScheduleByIdRequest $in
     * @return TourScheduleByIdResponse
     */
    public function GetTourScheduleById(ContextInterface $ctx, GetTourScheduleByIdRequest $in): TourScheduleByIdResponse;

    /**
     * List tour schedules by tour ID
     *
     * @param ContextInterface $ctx
     * @param GetListTourScheduleByTourIdRequest $in
     * @return ListTourScheduleByTourIdResponse
     */
    public function GetListTourScheduleByTourId(ContextInterface $ctx, GetListTourScheduleByTourIdRequest $in): ListTourScheduleByTourIdResponse;
}
