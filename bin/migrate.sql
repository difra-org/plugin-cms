INSERT INTO `user` (`id`, `email`, `password`, `active`, `banned`, `registered`, `lastseen`, `activation`, `info`) SELECT
                                                                                                                       `id`,
                                                                                                                       `email`,
                                                                                                                       `password`,
                                                                                                                       `active`,
                                                                                                                       `banned`,
                                                                                                                       `registered`,
                                                                                                                       `logged`,
                                                                                                                       `activation`,
                                                                                                                       `info`
                                                                                                                   FROM
                                                                                                                       `users`;
