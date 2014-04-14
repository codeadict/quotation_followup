# -*- coding: utf-8 -*-
# Copyright (C) 2014 G&D Systems
# Developer: Dairon Medina Caro <info@gydsystems.com>

{
    'name': 'Quotations FollowUp',
    'version': '1.0',
    'category': 'Sales',
    'complexity': 'easy',
    'description': """

    """,
    'author': 'G&D Systems',
    'website': 'http://www.gydsystems.com',
    'depends': ['sale', 'mail'],
    'data': [
        #'security/quotation_followup_security.xml',
        #'security/ir.model.access.csv',
        'quotation_followup_data.xml',
        'quotation_followup_view.xml',
    ],
    'installable': True,
    'auto_install': False,
}