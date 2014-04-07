# -*- coding: utf-8 -*-
# Copyright (C) 2014 G&D Systems
# Developer: Dairon Medina Caro <info@gydsystems.com>
from openerp.osv import fields, osv
from lxml import etree

from openerp.tools.translate import _


class followup(osv.osv):
    """
    Model for Quotation follow Up
    """
    _name = 'quotation_followup.followup'
    _description = 'Quotation Follow-up'
    _rec_name = 'name'
    _columns = {
        'followup_line': fields.one2many('quotation_followup.followup.line', 'followup_id', 'Follow-up'),
        'company_id': fields.many2one('res.company', 'Company', required=True),
        'name': fields.related('company_id', 'name', string="Name"),
    }
    _defaults = {
        'company_id': lambda s, cr, uid, c: s.pool.get('res.company').company_default_get(cr, uid,
                                                                                          'quotation_followup.followup',
                                                                                          context=c),
    }
    _sql_constraints = [('company_uniq', 'unique(company_id)', 'Only one follow-up per company is allowed')]


class followup_line(osv.osv):
    """
    Configuration of Follow Up
    """

    def _get_default_template(self, cr, uid, ids, context=None):
        """
        Gets the email template for sending
        """
        try:
            return self.pool.get('ir.model.data').get_object_reference(cr, uid, 'quotation_followup',
                                                                       'email_template_quotation_followup')[1]
        except ValueError:
            return False

    _columns = {
        'name': fields.char('Follow-Up Action', size=64, required=True),
        'sequence': fields.integer('Sequence', help="Gives the order when displaying list of follow-up lines."),
        'delay': fields.integer('Due Days',
                                help="The number of days after the quotation date to wait before sending "
                                     "the mail.",
                                required=True),
        'followup_id': fields.many2one('quotation_followup.followup', 'Follow Ups', required=True, ondelete="cascade"),
        'email_template_id': fields.many2one('email.template', 'Email Template', ondelete='set null'),
    }

    _order = 'delay'
    _sql_constraints = [('days_uniq', 'unique(followup_id, delay)', 'You must send mails on different days.')]
    _defaults = {
        'email_template_id': _get_default_template,
    }