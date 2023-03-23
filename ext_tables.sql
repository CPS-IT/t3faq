#
# Table definition for "tx_t3faq_domain_model_faq_question"
#
CREATE TABLE tx_t3faq_domain_model_question
(
    uid              int(11)                  NOT NULL auto_increment,
    pid              int(11)      DEFAULT '0' NOT NULL,
    tstamp           int(11)      DEFAULT '0' NOT NULL,
    crdate           int(11)      DEFAULT '0' NOT NULL,
    cruser_id        int(11)      DEFAULT '0' NOT NULL,
    deleted          tinyint(3)   DEFAULT '0',
    hidden           tinyint(4)   DEFAULT '0' NOT NULL,
    starttime        int(11)      DEFAULT '0' NOT NULL,
    endtime          int(11)      DEFAULT '0' NOT NULL,
    fe_group         varchar(100) DEFAULT '0' NOT NULL,
    sorting          int(11)      DEFAULT '0' NOT NULL,

    question         varchar(300) DEFAULT ''  NOT NULL,
    answer           text,
    categories       int(11) UNSIGNED         NOT NULL DEFAULT '0',
    weight           tinyint(4)               NOT NULL DEFAULT '0',
    likes            int(11)                  NOT NULL DEFAULT '0',
    dislikes         int(11)                  NOT NULL DEFAULT '0',
    information_date int(11)      DEFAULT '0' NOT NULL,

    sys_language_uid int(11)      DEFAULT '0' NOT NULL,
    l10n_parent      int(11)      DEFAULT '0' NOT NULL,
    l10n_diffsource  mediumblob,

    t3ver_oid        int(11)      DEFAULT '0' NOT NULL,
    t3ver_id         int(11)      DEFAULT '0' NOT NULL,
    t3ver_wsid       int(11)      DEFAULT '0' NOT NULL,
    t3ver_label      varchar(30)  DEFAULT ''  NOT NULL,
    t3ver_state      tinyint(4)   DEFAULT '0' NOT NULL,
    t3ver_stage      tinyint(4)   DEFAULT '0' NOT NULL,
    t3ver_count      int(11)      DEFAULT '0' NOT NULL,
    t3ver_tstamp     int(11)      DEFAULT '0' NOT NULL,
    t3ver_move_id    int(11)      DEFAULT '0' NOT NULL,
    t3_origuid       int(11)      DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY sys_language_uid_l10n_parent (sys_language_uid, l10n_parent)
);

#
# Extend table structure of table 'sys_category'
#
CREATE TABLE sys_category
(
    faqs int(11) UNSIGNED NOT NULL DEFAULT '0',
    frame_class varchar(60) DEFAULT '' NOT NULL
);
